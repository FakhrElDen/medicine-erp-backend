const http = require('http');
const socketio = require('socket.io');
const Redis = require('ioredis');

// Create an HTTP server instance
const server = http.createServer();

// Create a Socket.io instance with CORS enabled for any origin
const io = socketio(server, {
    cors: {
        origin: '*',
    },
});

// Connect to Redis server on port 6379 and host "redis"
const redisEvents = new Redis(6379, 'redis');
const redisData = new Redis(6379, 'redis');


// Handle connection event for each client
io.on('connection', (socket) => {
    console.log(`Client connected: ${socket.id}`);

    // Handle subscription to the channel
    socket.on('subscribe', (channel) => {
        if (channel === 'settlement') {
            redisData.get("settlement").then((result) => {
                // Send a message upon subscription
                socket.emit('settlement', {count: result});
            });
        }
        else if (channel === 'transfers') {
            redisData.get("transfers").then((result) => {
                // Send a message upon subscription
                socket.emit('transfers', {count: result});
            });
        }
        else if (channel === 'inventory') {
            redisData.get("inventory").then((result) => {
                // Send a message upon subscription
                socket.emit('inventory', {count: result});
            });
        }
    });
});

// Subscribe to the "orders_count" channel in Redis
redisEvents.subscribe('settlement', 'transfers', 'inventory', 'bulk_preparing', 'preparing', 'housing', 'receipt', (error, count) => {
    if (error) {
        console.error('Error subscribing to Redis channel:', error);
        return;
    }

    console.log(`successfully subscribed to ${count} channels`);
});

// Listen for messages on the subscribed channel
redisEvents.on('message', (channel, message) => {
    try {
        // Parse the received message as JSON
        let data = JSON.parse(message).data;

        console.error(`message received on ${channel} channel`);
        let counts_array = data.count ?? (data.orders_count ?? data.batches_count);
        console.error({
            channel: channel,
            action: data.action,
            counts: Array.isArray(counts_array) ? counts_array.map(function (item) { return { corridor_id: item.corridor_id, count: item.count }; }) : counts_array
        });

        // Emit the order count update to the specific client based on user ID and event
        io.emit(`${channel}`, data);

    } catch (error) {
        console.error('Error processing message:', error);
    }
});

// Start the server on port 3000
server.listen(3000, () => {
    console.log('Socket.IO server listening on port 3000');
});

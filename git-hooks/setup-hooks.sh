#!/bin/bash

# Determine the operating system
if [[ "$OSTYPE" == "msys" || "$OSTYPE" == "cygwin" ]]; then
    # Windows using MSYS or Cygwin
    cp ..\\git-hooks\\pre-commit ..\\.git\\hooks\\pre-commit
else
    # Assume Unix-like operating system (Linux or macOS)
    if [ ! -e .git/hooks/pre-commit ]; then
        ln -s ../git-hooks/pre-commit ../.git/hooks/pre-commit
    fi
fi






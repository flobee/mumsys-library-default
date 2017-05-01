# Mumsys logger interface

## Classes to work with log messages

- Standard file logging, limited functionality, just pipe text to a file

- Default uses the file logging 
    OLD: including message output functionality. 
    NEW: From now on use the message decorator instead


## Files and introduction:

- Mumsys_Logger_Default
    Rewrite of Mumsys_Logger.php/class.logger.php. 
    This is the new version containing all the old 
    features in a new costume. For maximum performance and minimum features please check 
    Mumsys_Logger_File or with some decorators you may interested in.

- Mumsys_Logger_File
    Simple logger to store log messages to a log file for maximum performance.

- Mumsys_Logger_Default 
    Wrapper for the file logger which is the default

- Mumsys_Logger_Decorator_Messages
    Decorates the file logger to have also console output with or without some colors


   
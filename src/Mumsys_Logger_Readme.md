# Mumsys logger interface

## Classes to work with log messages and output of messages e.g. for the shell

- Standard file logging, limited functionality, just pipe text to a file

- Default uses the file logging including message output functionality. enhanced version


## Files and introdution:

- Mumsys_Logger_Default
    Rewrite of Mumsys_Logger.php/class.logger.php. This is the new version containing all the old 
    features in a new costume. For maximum performance and minimum features please check 
    Mumsys_Logger_File or with some decorators you may interested in.

- Mumsys_Logger_File
    Simple logger to store log massages to a log file for maximum performance.


   
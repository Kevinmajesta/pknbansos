dbxopenmysql50.dll

Added : use other port other than default
 syntax : hostname:port


Struktur direktori yang dibutuhkan oleh aplikasi RepEngine

- RepEngine.exe
- file-file driver database yang diperlukan DBExpress dan Zeos
+ FR3
  - berisi file FR3
+ Output
  - berisi file hasil export dari RepEngine.exe


Format input :
ReportName=[fast report file]
OutputType=[html] [pdf] [xls]
Connection=[DBExpress] [Zeos]
DriverName=[Firebird/Interbase/Ibase] [MySQL] [MSSQL] [PostgreSQL]
DBServer=[server name/ip address]
DBPort=[port number]
DBName=[database name/database file]
DBUser=[username]
DBPassword=[password]
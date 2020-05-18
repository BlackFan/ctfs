<?php
  $payload = 
    "\x04\x01\x82\x8c\x04".                                                       /* jvm CONSTANT_Float | elisp GOTO */
    "\x05\xff\xff\xff\x32\x01\x00\x00\x00\x05\x00\x00\x17\x00\x49\x80\x00\x00".   /* jvm CONSTANT_Long  | lua   JMP  */
    str_repeat("\x01\x00\x00", 216);                                              /* jvm CONSTANT_Utf8  | padding    */
  
  $pcc = 221;
  $constant_pool = 36 + $pcc;                                                     /* 257 constants so that the file starts with 0101 */
  
  $class = fopen('Bytecode.class','w');                                           /* Rest of the class data with the correct constant-pool indexes */
  fwrite($class, "\xca\xfe\xba\xbe\x00\x00\x00\x34");
  fwrite($class, pack('n', $constant_pool));
  fwrite($class, $payload);
  fwrite($class, "\x01\x00\x10java/lang/Object");
  fwrite($class, pack('cnn',9,3+$pcc,4+$pcc));
  fwrite($class, pack('cn',7,5+$pcc));
  fwrite($class, pack('cnn',12,6+$pcc,7+$pcc));
  fwrite($class, "\x01\x00\x10java/lang/System");
  fwrite($class, "\x01\x00\x03out");
  fwrite($class, "\x01\x00\x15Ljava/io/PrintStream;");
  fwrite($class, pack('cn',8,9+$pcc));
  fwrite($class, "\x01\x00\x04flag");
  fwrite($class, pack('cn',7,11+$pcc));
  fwrite($class, "\x01\x00\x10java/lang/String");
  fwrite($class, pack('cnn',10,13+$pcc,14+$pcc));
  fwrite($class, pack('cn',7,15+$pcc));
  fwrite($class, pack('cnn',12,16+$pcc,17+$pcc));
  fwrite($class, "\x01\x00\x13java/nio/file/Paths");
  fwrite($class, "\x01\x00\x03get");
  fwrite($class, "\x01\x00\x3b(Ljava/lang/String;[Ljava/lang/String;)Ljava/nio/file/Path;");
  fwrite($class, pack('cnn',10,19+$pcc,20+$pcc));
  fwrite($class, pack('cn',7,21+$pcc));
  fwrite($class, pack('cnn',12,22+$pcc,23+$pcc));
  fwrite($class, "\x01\x00\x13java/nio/file/Files");
  fwrite($class, "\x01\x00\x0creadAllLines");
  fwrite($class, "\x01\x00\x26(Ljava/nio/file/Path;)Ljava/util/List;");
  fwrite($class, pack('cnn',10,25+$pcc,26+$pcc));
  fwrite($class, pack('cn',7,27+$pcc));
  fwrite($class, pack('cnn',12,28+$pcc,29+$pcc));
  fwrite($class, "\x01\x00\x13java/io/PrintStream");
  fwrite($class, "\x01\x00\x07println");
  fwrite($class, "\x01\x00\x15(Ljava/lang/Object;)V");
  fwrite($class, pack('cn',7,31+$pcc));
  fwrite($class, "\x01\x00\x08Bytecode");
  fwrite($class, "\x01\x00\x04Code");
  fwrite($class, "\x01\x00\x04main");
  fwrite($class, "\x01\x00\x16([Ljava/lang/String;)V");
  fwrite($class, pack('cn',7,1+$pcc));
  fwrite($class, pack('nnnnnnnnnn',33,30+$pcc,35+$pcc,0,0,1,9,33+$pcc,34+$pcc,1));
  fwrite($class, pack('nNnnN',32+$pcc,31,3,1,19));
  fwrite($class, pack('cn',0xB2,2+$pcc));
  fwrite($class, pack('cc',0x12,8+$pcc));
  fwrite($class, pack('c',0x03));
  fwrite($class, pack('cn',0xBD,10+$pcc));
  fwrite($class, pack('cn',0xB8,12+$pcc));
  fwrite($class, pack('cn',0xB8,18+$pcc));
  fwrite($class, pack('cn',0xB6,24+$pcc));
  fwrite($class, pack('c',0xB1));     

  $payload2 = 
    /* elisp bytecode */
    "\xDB\xDA\xC5\xCB\xC0\xC6\x24\x21\x88\xDC\xDD\x20\x21\x87".
    /* padding */
    "\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00".
    /* lua bytecode + constants */
    "\x06\x00\x40\x00\x46\x40\x40\x00\x47\x80\xC0\x00\x81\xC0\x00\x00\xC1\x00\x01\x00\x5D\x80\x80\x01\x4C\x40\xC1\x00\xC1\x80\x01\x00\x5D\x00\x80\x01\x1D\x40\x00\x00\x1F\x00\x80\x00\x07\x00\x00\x00\x04\x06\x00\x00\x00\x00\x00\x00\x00\x70\x72\x69\x6E\x74\x00\x04\x03\x00\x00\x00\x00\x00\x00\x00\x69\x6F\x00\x04\x05\x00\x00\x00\x00\x00\x00\x00\x6F\x70\x65\x6E\x00\x04\x05\x00\x00\x00\x00\x00\x00\x00\x66\x6C\x61\x67\x00\x04\x03\x00\x00\x00\x00\x00\x00\x00\x72\x62\x00\x04\x05\x00\x00\x00\x00\x00\x00\x00\x72\x65\x61\x64\x00\x04\x03\x00\x00\x00\x00\x00\x00\x00\x2A\x61\x00\x00\x00\x00\x00\x01\x00\x00\x00\x01\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00".
    /* pytnon2 + python3 zip */
    "\x50\x4B\x03\x04\x14\x00\x00\x00\x08\x00\x71\x50\xB2\x50\x54\x19\x5A\x13\x21\x00\x00\x00\x1F\x00\x00\x00\x0B\x00\x00\x00\x5F\x5F\x6D\x61\x69\x6E\x5F\x5F\x2E\x70\x79\x2B\x28\xCA\xCC\x2B\xD1\xC8\x2F\x48\xCD\xD3\x50\x4A\xCB\x49\x4C\x57\xD2\x51\x50\x2A\x52\xD2\xD4\x2B\x4A\x4D\x4C\xD1\xD0\xD4\x04\x00\x50\x4B\x01\x02\x1F\x00\x14\x00\x00\x00\x08\x00\x71\x50\xB2\x50\x54\x19\x5A\x13\x21\x00\x00\x00\x1F\x00\x00\x00\x0B\x00\x24\x00\x00\x00\x00\x00\x00\x00\x20\x00\x00\x00\x00\x00\x00\x00\x5F\x5F\x6D\x61\x69\x6E\x5F\x5F\x2E\x70\x79\x0A\x00\x20\x00\x00\x00\x00\x00\x01\x00\x18\x00\x97\x17\x7D\x10\xDA\x2C\xD6\x01\x97\x17\x7D\x10\xDA\x2C\xD6\x01\x7B\xB7\xFE\x58\xD9\x2C\xD6\x01\x50\x4B\x05\x06\x00\x00\x00\x00\x01\x00\x01\x00\x5D\x00\x00\x00\x4A\x00\x00\x00\x00\x00";

  /* payload2 in unused attribute */
  fwrite($class, pack('nnn',0,0,1));   
  fwrite($class, pack('nN',33+$pcc,strlen($payload2)));
  fwrite($class, $payload2);

  /* elisp goto fix */
  $x = ftell($class) - strlen($payload2) - 8;
  fseek($class, 13);
  fwrite($class, pack('S', $x));
  fclose($class);

  $bytecode = substr(file_get_contents('Bytecode.class'),8);
  file_put_contents('bytecode.luac', "\x1B\x4C\x75\x61\x52\x00\x01\x04\x08\x04\x08\x00\x19\x93\x0D\x0A\x1A\x0A".$bytecode);
  file_put_contents('bytecode', $bytecode);
?>
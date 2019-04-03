Install php 7.1.3 or 7.1.4 version (there is an issue for symfony 4 with php < 7 and 7.2)
Install php composer
install symfony 4
Add header with   ---  x-wsse :'Username="ryan", PasswordDigest="ryanpass", Nonce="0", Created="2019-03-26"'', while calling api (small custom authentication is added)
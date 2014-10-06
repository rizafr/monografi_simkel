 function TrimString(sInString) {
   sInString = sInString.replace( /^\s+/g, "" );// strip leading
   return sInString.replace( /\s+$/g, "" );// strip trailing
 }
 
 function formatCurrency(num) {
   num = num.toString().replace(/\$|\,/g,'');
   if (isNaN(num))
     num = "0";
     sign = (num == (num = Math.abs(num)));
     num = Math.floor(num*100+0.50000000001);
     cents = num%100;
     num = Math.floor(num/100).toString();
   if (cents<10)
     cents = "0" + cents;
   for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
     num = num.substring(0,num.length-(4*i+3))+'.'+num.substring(num.length-(4*i+3));
   return (((sign)?'':'-') + num + ',' + cents);
 }
 
 function deformatCurrency(num) {
   num = num.toString().replace(/\$|\./g,'');
   num = num.toString().replace(/\$|\,/g,'.');
   if (isNaN(num))
     num = "0";
     sign = (num == (num = Math.abs(num)));
     num = Math.floor(num*100+0.50000000001);
     cents = num%100;
     num = Math.floor(num/100).toString();
   if (cents<10)
     cents = "0" + cents;
   for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
     num = num.substring(0,num.length-(4*i+3))+num.substring(num.length-(4*i+3));
   return (((sign)?'':'-') + num + '.' + cents);
 }
 
 function formatCurrencyNoDec(num) {
   num = num.toString().replace(/\$|\,/g,'.');
   if (isNaN(num))
     num = "0";
     sign = (num == (num = Math.abs(num)));
     num = Math.floor(num*100+0.50000000001);
     cents = num%100;
     num = Math.floor(num/100).toString();
   if (cents<10)
     cents = "0" + cents;
   for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
     num = num.substring(0,num.length-(4*i+3))+'.'+num.substring(num.length-(4*i+3));
   return (((sign)?'':'-') + num);
 }
 
 function deformatCurrencyNoDec(num) {
   num = num.toString().replace(/\$|\./g,'');
   num = num.toString().replace(/\$|\,/g,'.');
   if (isNaN(num))
     num = "0";
     sign = (num == (num = Math.abs(num)));
     num = Math.floor(num*100+0.50000000001);
     cents = num%100;
     num = Math.floor(num/100).toString();
   if (cents<10)
     cents = "0" + cents;
   for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
     num = num.substring(0,num.length-(4*i+3))+num.substring(num.length-(4*i+3));
   return (((sign)?'':'-') + num);
 }
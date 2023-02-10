function newWindow(file,window) {
    msgWindow=open(file,window,"resizable=no,width=400,height=150");
    if (msgWindow.opener == null) msgWindow.opener = self;
}

function newWindow1(file,window) {
    msgWindow=open(file,window,"resizable=yes,width=600,height=650");
    if (msgWindow.opener == null) msgWindow.opener = self;
}
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}

function copyData1(from,to) { to.value = (document.acsForm.ncas_freight.value-0) + (document.acsForm.ncas_invoice_amount.value-0); }

function copyData2(from,to) { to.value = (document.acsForm.ncas_freight.value-0) + (document.acsForm.ncas_invoice_amount.value-0); }

document.acsForm.vendor_number.value = location.search.substring(1);
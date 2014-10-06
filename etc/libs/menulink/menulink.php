<script>

function cariDataPribadi(i_nik,n_nama)
{
	url = "<?php echo $this->basePath; ?>/kp/datapokok/datapokokubah"	
	var opt = {i_nik:i_nik,n_nama:n_nama};
	jQuery.getScript('<?php echo $this->basePath; ?>/kp/datapokok/datapokoklistjs');
	jQuery.get(url,opt,function(data) {	
		$("#tableview").html(data);			
			$("#frmcatatbiodata").ajaxForm(option_tambahdatadiri);
		});
}
function cariDataKesehatan(i_nik,n_nama)
{
	url = "<?php echo $this->basePath; ?>/kp/datakesehatan/datakesehatan"	
	var opt = {i_nik:i_nik,n_nama:n_nama};
	jQuery.getScript('<?php echo $this->basePath; ?>/kp/datakesehatan/datakesehatanjs');
	jQuery.get(url,opt,function(data) {	
		$("#tableview").html(data);			
			$("#frmcatatkesehatan").ajaxForm(option_tambahdatakesehatan);
		});
}


function cariPendidikan(i_nik,n_nama)
{
	url = "<?php echo $this->basePath; ?>/kp/datapendidikan/datapendidikan"	
	var opt = {i_nik:i_nik,n_nama:n_nama};
	jQuery.getScript('<?php echo $this->basePath; ?>/kp/datapendidikan/datapendidikanjs');
	jQuery.get(url,opt,function(data) {	
		$("#tableview").html(data);			
			$("#frmcatatpendidikan").ajaxForm(option_tambahdatapendidikan);
		});
}

function cariGolonganPangkat(i_nik,n_nama)
{
	url = "<?php echo $this->basePath; ?>/kp/datapangkat/datapangkat"	
	var opt = {i_nik:i_nik,n_nama:n_nama};
	jQuery.getScript('<?php echo $this->basePath; ?>/kp/datapangkat/datapangkatjs');
	jQuery.get(url,opt,function(data) {	
		$("#tableview").html(data);			
			$("#frmcatatpangkat").ajaxForm(option_tambahdatapangkat);
		});
}
function cariPangkatAkademik(i_nik,n_nama)
{
	url = "<?php echo $this->basePath; ?>/kp/datakepangkatan/datakepangkatan"	
	var opt = {i_nik:i_nik,n_nama:n_nama};
	jQuery.getScript('<?php echo $this->basePath; ?>/kp/datakepangkatan/datakepangkatanjs');
	jQuery.get(url,opt,function(data) {	
		$("#tableview").html(data);			
			$("#frmcatatkepangkatan").ajaxForm(option_tambahdatakepangkatan);
		});
}
function cariRiwayatPekerjaan(i_nik,n_nama)
{
	url = "<?php echo $this->basePath; ?>/kp/datajabatan/datajabatan"	
	var opt = {i_nik:i_nik,n_nama:n_nama};
	jQuery.getScript('<?php echo $this->basePath; ?>/kp/datajabatan/datajabatanjs');
	jQuery.get(url,opt,function(data) {	
		$("#tableview").html(data);			
			$("#frmcatatjabatan").ajaxForm(option_tambahdatajabatan);
		});
}
function cariPelatihan(i_nik,n_nama)
{
	url = "<?php echo $this->basePath; ?>/kp/datapelatihan/datapelatihan"	
	var opt = {i_nik:i_nik,n_nama:n_nama};
	jQuery.getScript('<?php echo $this->basePath; ?>/kp/datapelatihan/datapelatihanjs');
	jQuery.get(url,opt,function(data) {	
		$("#tableview").html(data);			
			 $("#frmcatatpelatihan").ajaxForm(option_tambahdatapelatihan);
		});
}
function cariSeminar(i_nik,n_nama)
{
	url = "<?php echo $this->basePath; ?>/kp/dataseminar/dataseminar"	
	var opt = {i_nik:i_nik,n_nama:n_nama};
	jQuery.getScript('<?php echo $this->basePath; ?>/kp/dataseminar/dataseminarjs');
	jQuery.get(url,opt,function(data) {	
		$("#tableview").html(data);			
			 $("#frmcatatseminar").ajaxForm(option_tambahdataseminar);
		});
}
function cariPenelitian(i_nik,n_nama)
{
	url = "<?php echo $this->basePath; ?>/kp/datapenelitian/datapenelitian"	
	var opt = {i_nik:i_nik,n_nama:n_nama};
	jQuery.getScript('<?php echo $this->basePath; ?>/kp/datapenelitian/datapenelitianjs');
	jQuery.get(url,opt,function(data) {	
		$("#tableview").html(data);			
			 $("#frmcatatpenelitian").ajaxForm(option_tambahdatapenelitian);
		});
}
function cariPublikasi(i_nik,n_nama)
{
	url = "<?php echo $this->basePath; ?>/kp/datapublikasi/datapublikasi"	
	var opt = {i_nik:i_nik,n_nama:n_nama};
	jQuery.getScript('<?php echo $this->basePath; ?>/kp/datapublikasi/datapublikasijs');
	jQuery.get(url,opt,function(data) {	
		$("#tableview").html(data);			
			 $("#frmcatatpublikasi").ajaxForm(option_tambahdatapublikasi);
		});
}
function cariLuarNegri(i_nik,n_nama)
{
	url = "<?php echo $this->basePath; ?>/kp/dataluarnegri/dataluarnegri"	
	var opt = {i_nik:i_nik,n_nama:n_nama};
	jQuery.getScript('<?php echo $this->basePath; ?>/kp/dataluarnegri/dataluarnegrijs');
	jQuery.get(url,opt,function(data) {	
		$("#tableview").html(data);			
			 $("#frmcatatluarnegri").ajaxForm(option_tambahdataluarnegri);
		});
}
function cariPenugasan(i_nik,n_nama)
{
	url = "<?php echo $this->basePath; ?>/kp/datapenugasan/datapenugasan"	
	var opt = {i_nik:i_nik,n_nama:n_nama};
	jQuery.getScript('<?php echo $this->basePath; ?>/kp/datapenugasan/penugasanjs');
	jQuery.get(url,opt,function(data) {	
		$("#tableview").html(data);			
			 $("#frmcatatpenugasan").ajaxForm(option_tambahdatapenugasan);
		});
}
function cariSertifikasi(i_nik,n_nama)
{
	url = "<?php echo $this->basePath; ?>/kp/datasertifikasi/datasertifikasi"	
	var opt = {i_nik:i_nik,n_nama:n_nama};
	jQuery.getScript('<?php echo $this->basePath; ?>/kp/datasertifikasi/datasertifikasijs');
	jQuery.get(url,opt,function(data) {	
		$("#tableview").html(data);			
			 $("#frmcatatsertifikat").ajaxForm(option_tambahdatasertifikasi);
		});
}
function cariKeluarga(i_nik,n_nama)
{
	url = "<?php echo $this->basePath; ?>/kp/datakeluarga/datakeluarga"	
	var opt = {i_nik:i_nik,n_nama:n_nama};
	jQuery.getScript('<?php echo $this->basePath; ?>/kp/datakeluarga/datakeluargajs');
	jQuery.get(url,opt,function(data) {	
		$("#tableview").html(data);			
			$("#frmcatatkeluarga").ajaxForm(option_tambahdatakeluarga);
		});
}
function cariOrtu(i_nik,n_nama)
{
	url = "<?php echo $this->basePath; ?>/kp/dataorangtua/dataorangtua"	
	var opt = {i_nik:i_nik,n_nama:n_nama};
	jQuery.getScript('<?php echo $this->basePath; ?>/kp/dataorangtua/dataorangtuajs');
	jQuery.get(url,opt,function(data) {	
		$("#tableview").html(data);			
			$("#frmcatatorangtua").ajaxForm(option_tambahdataorangtua);
		});
}
function cariPhoto(i_nik,n_nama)
{
	url = "<?php echo $this->basePath; ?>/kp/dataphoto/dataphoto"	
	var opt = {i_nik:i_nik,n_nama:n_nama};
	jQuery.getScript('<?php echo $this->basePath; ?>/kp/dataphoto/dataphotojs');
	jQuery.get(url,opt,function(data) {	
		$("#tableview").html(data);			
			$("#frmcatatphoto").ajaxForm(option_tambahdataphoto);
		});
}
function cariCiriFisik(i_nik,n_nama)
{
	url = "<?php echo $this->basePath; ?>/kp/datacirifisik/datacirifisik"	
	var opt = {i_nik:i_nik,n_nama:n_nama};
	jQuery.getScript('<?php echo $this->basePath; ?>/kp/datacirifisik/datacirifisikjs');
	jQuery.get(url,opt,function(data) {	
		$("#tableview").html(data);			
			$("#frmcatatcirifisik").ajaxForm(option_tambahdatacirifisik);
		});
}
function cariPenghargaan(i_nik,n_nama)
{
	url = "<?php echo $this->basePath; ?>/kp/datapenghargaan/datapenghargaan"	
	var opt = {i_nik:i_nik,n_nama:n_nama};
	jQuery.getScript('<?php echo $this->basePath; ?>/kp/datapenghargaan/datapenghargaanjs');
	jQuery.get(url,opt,function(data) {	
		$("#tableview").html(data);			
			$("#frmcatatpenghargaan").ajaxForm(option_tambahdatapenghargaan);
		});
}
function cariOrganisasi(i_nik,n_nama)
{
	url = "<?php echo $this->basePath; ?>/kp/dataorganisasi/dataorganisasi"	
	var opt = {i_nik:i_nik,n_nama:n_nama};
	jQuery.getScript('<?php echo $this->basePath; ?>/kp/dataorganisasi/dataorganisasijs');
	jQuery.get(url,opt,function(data) {	
		$("#tableview").html(data);			
			$("#frmcatatorganisasi").ajaxForm(option_tambahdataorganisasi);
		});
}
function cariSanksi(i_nik,n_nama)
{
	url = "<?php echo $this->basePath; ?>/kp/datasanksi/datasanksi"	
	var opt = {i_nik:i_nik,n_nama:n_nama};
	jQuery.getScript('<?php echo $this->basePath; ?>/kp/datasanksi/datasanksijs');
	jQuery.get(url,opt,function(data) {	
		$("#tableview").html(data);			
			$("#frmcatatsanksi").ajaxForm(option_tambahdatasanksi);
		});
}
function cariAlamat(i_nik,n_nama)
{
	url = "<?php echo $this->basePath; ?>/kp/dataalamat/dataalamat"	
	var opt = {i_nik:i_nik,n_nama:n_nama};
	jQuery.getScript('<?php echo $this->basePath; ?>/kp/dataalamat/dataalamatjs');
	jQuery.get(url,opt,function(data) {	
		$("#tableview").html(data);			
			$("#frmcatatalamat").ajaxForm(option_tambahdataalamat);
		});
}

var option_tambahdataalamat = {
	target:'#tableview',
	beforeSubmit: function() { 
					aobj= document.frmcatatalamat;
					astr= "document.frmcatatalamat";
					var cekVal = validasidataalamat(aobj,astr);
					if (cekVal == false) {
					return false;}
						}, 
	success:masukdataalamatsukses,
	url: '<?php echo $this->basePath; ?>/kp/dataalamat/maintaindata',
	type: 'post',
	resetForm: false
};

function masukdataalamatsukses() {
	location.href="#top";
	doCounter(5);
	$("#frmcatatalamat").ajaxForm(option_tambahdataalamat);
}

function validasidataalamat(xy,xstr)
{
	
   with(xy){
      
         var Proceed = 1;
         var Message;
         var FocusField;
         while (Proceed == 1)
         {

			if((!n_alamat.value)){
               Message = "Data Alamat tidak boleh kosong";
               FocusField = "n_alamat";
               Proceed = 0;
               break;
            }
            break;
         }
        
         if (Proceed == 1){ 
            return true;
         }else{
            alert( Message );
            eval(xstr+"."+FocusField+".focus();");           	
            return false;
         }
   }
}

var option_tambahdatacirifisik = {
	target:'#tableview',
	beforeSubmit: function() { 
					aobj= document.frmcatatcirifisik;
					astr= "document.frmcatatcirifisik";
					var cekVal = validasidatacirifisik(aobj,astr);
					if (cekVal == false) {
					return false;}
						}, 
	success:masukdatacirifisiksukses,
	url: '<?php echo $this->basePath; ?>/kp/datacirifisik/maintaindata',
	type: 'post',
	resetForm: false
};

function masukdatacirifisiksukses() {
	location.href="#top";
	doCounter(5);
	$("#frmcatatcirifisik").ajaxForm(option_tambahdatacirifisik);
}

function validasidatacirifisik(xy,xstr)
{
	
   with(xy){
      
         var Proceed = 1;
         var Message;
         var FocusField;
         while (Proceed == 1)
         {

			if(!i_nik.value){
               Message = "Data Nip Dari tidak boleh kosong";
               FocusField = "i_nik";
               Proceed = 0;
               break;
            }
			if((!n_nama.value)){
               Message = "Data Nama tidak boleh kosong";
               FocusField = "n_nama";
               Proceed = 0;
               break;
            }
			if((!q_tinggi.value)){
               Message = "Tinggi Badan tidak boleh kosong";
               FocusField = "q_tinggi";
               Proceed = 0;
               break;
            }
			if((!q_berat.value)){
               Message = "Berat Badan tidak boleh kosong";
               FocusField = "q_berat";
               Proceed = 0;
               break;
            }
            break;
         }
        
         if (Proceed == 1){ 
            return true;
         }else{
            alert( Message );
            eval(xstr+"."+FocusField+".focus();");           	
            return false;
         }
   }
}

var option_tambahdatadiklat = {
	target:'#tableview',
	beforeSubmit: function() { 
					aobj= document.frmcatatdiklat;
					astr= "document.frmcatatdiklat";
					var cekVal = validasidatadiklat(aobj,astr);
					if (cekVal == false) {
					return false;}
						}, 
	success:masukdatadiklatsukses,
	url: '<?php echo $this->basePath; ?>/kp/datadiklat/maintaindata',
	type: 'post',
	resetForm: false
};

function masukdatadiklatsukses() {
	location.href="#top";
	doCounter(5);
	$("#frmcatatdiklat").ajaxForm(option_tambahdatadiklat);
}

function validasidatadiklat(xy,xstr)
{
	
   with(xy){
      
         var Proceed = 1;
         var Message;
         var FocusField;
         while (Proceed == 1)
         {

			if(!i_nik.value){
               Message = "Data Nip Dari tidak boleh kosong";
               FocusField = "i_nik";
               Proceed = 0;
               break;
            }
			if((!n_nama.value)){
               Message = "Data Nama tidak boleh kosong";
               FocusField = "n_nama";
               Proceed = 0;
               break;
            }
			if((!n_pend_latih.value)){
               Message = "Nama Diklat tidak boleh kosong";
               FocusField = "n_pend_latih";
               Proceed = 0;
               break;
            }

            break;
         }
        
         if (Proceed == 1){ 
            return true;
         }else{
            alert( Message );
            eval(xstr+"."+FocusField+".focus();");           	
            return false;
         }
   }
}

var option_tambahdatajabatan = {
	target:'#tableview',
	beforeSubmit: function() { 
					aobj= document.frmcatatjabatan;
					astr= "document.frmcatatjabatan";
					var cekVal = validasidatajabatan(aobj,astr);
					if (cekVal == false) {
					return false;}
						}, 
	success:masukdatajabatansukses,
	url: '<?php echo $this->basePath; ?>/kp/datajabatan/maintaindata',
	type: 'post',
	resetForm: false
};

function masukdatajabatansukses() {
	location.href="#top";
	doCounter(5);
	$("#frmcatatjabatan").ajaxForm(option_tambahdatajabatan);
}

function validasidatajabatan(xy,xstr)
{
	
   with(xy){
      
         var Proceed = 1;
         var Message;
         var FocusField;
         while (Proceed == 1)
         {

			if(!i_nik.value){
               Message = "Data Nip Dari tidak boleh kosong";
               FocusField = "i_nik";
               Proceed = 0;
               break;
            }
			if((!n_nama.value)){
               Message = "Data Nama tidak boleh kosong";
               FocusField = "n_nama";
               Proceed = 0;
               break;
            }
			if((!c_jabatan.value)){
               Message = "Jenjang jabatan tidak boleh kosong";
               FocusField = "c_jabatan";
               Proceed = 0;
               break;
            }

            break;
         }
        
         if (Proceed == 1){ 
            return true;
         }else{
            alert( Message );
            eval(xstr+"."+FocusField+".focus();");           	
            return false;
         }
   }
}
var option_tambahdatakeluarga = {
	target:'#tableview',
	beforeSubmit: function() { 
					aobj= document.frmcatatkeluarga;
					astr= "document.frmcatatkeluarga";
					var cekVal = validasidatakeluarga(aobj,astr);
					if (cekVal == false) {
					return false;}
						}, 
	success:masukdatakeluargasukses,
	url: '<?php echo $this->basePath; ?>/kp/datakeluarga/maintaindata',
	type: 'post',
	resetForm: false
};

function masukdatakeluargasukses() {
	location.href="#top";
	doCounter(5);
	$("#frmcatatkeluarga").ajaxForm(option_tambahdatakeluarga);
}

function validasidatakeluarga(xy,xstr)
{
	
   with(xy){
      
         var Proceed = 1;
         var Message;
         var FocusField;
         while (Proceed == 1)
         {

			if(!i_nik.value){
               Message = "Data Nip Dari tidak boleh kosong";
               FocusField = "i_nik";
               Proceed = 0;
               break;
            }
			if((!c_keluarga.value)){
               Message = "Hubungan Keluarga tidak boleh kosong";
               FocusField = "c_keluarga";
               Proceed = 0;
               break;
            }
			if((!q_keluarga.value)){
               Message = "Anak ke... tidak boleh kosong";
               FocusField = "q_keluarga";
               Proceed = 0;
               break;
            }
			if((!n_namakeluarga.value)){
               Message = "Nama Keluarga tidak boleh kosong";
               FocusField = "n_namakeluarga";
               Proceed = 0;
               break;
            }			
            break;
         }
        
         if (Proceed == 1){ 
            return true;
         }else{
            alert( Message );
            eval(xstr+"."+FocusField+".focus();");           	
            return false;
         }
   }
}

var option_tambahdatakepangkatan = {
	target:'#tableview',
	beforeSubmit: function() { 
					aobj= document.frmcatatkepangkatan;
					astr= "document.frmcatatkepangkatan";
					var cekVal = validasidatakepangkatan(aobj,astr);
					if (cekVal == false) {
					return false;}
						}, 
	success:masukdatakepangkatansukses,
	url: '<?php echo $this->basePath; ?>/kp/datakepangkatan/maintaindata',
	type: 'post',
	resetForm: false
};

function masukdatakepangkatansukses() {
	location.href="#top";
	doCounter(5);
	$("#frmcatatkepangkatan").ajaxForm(option_tambahdatakepangkatan);
}

function validasidatakepangkatan(xy,xstr)
{
	
   with(xy){
      
         var Proceed = 1;
         var Message;
         var FocusField;
         while (Proceed == 1)
         {

			if(!i_nik.value){
               Message = "Data Nip Dari tidak boleh kosong";
               FocusField = "i_nik";
               Proceed = 0;
               break;
            }
			if((!n_nama.value)){
               Message = "Data Nama tidak boleh kosong";
               FocusField = "n_nama";
               Proceed = 0;
               break;
            }
			if((!c_jbtakademik.value)){
               Message = "jabatan tidak boleh kosong";
               FocusField = "c_jbtakademik";
               Proceed = 0;
               break;
            }

            break;
         }
        
         if (Proceed == 1){ 
            return true;
         }else{
            alert( Message );
            eval(xstr+"."+FocusField+".focus();");           	
            return false;
         }
   }
}

var option_tambahdatakesehatan = {
	target:'#tableview',
	beforeSubmit: function() { 
					aobj= document.frmcatatkesehatan;
					astr= "document.frmcatatkesehatan";
					var cekVal = validasidatakesehatan(aobj,astr);
					if (cekVal == false) {
					return false;}
						}, 
	success:masukdatakesehatansukses,
	url: '<?php echo $this->basePath; ?>/kp/datakesehatan/maintaindata',
	type: 'post',
	resetForm: false
};

function masukdatakesehatansukses() {
	location.href="#top";
	doCounter(5);
	$("#frmcatatkesehatan").ajaxForm(option_tambahdatakesehatan);
}

function validasidatakesehatan(xy,xstr)
{
	
   with(xy){
      
         var Proceed = 1;
         var Message;
         var FocusField;
         while (Proceed == 1)
         {

			if(!i_nik.value){
               Message = "Data Nip Dari tidak boleh kosong";
               FocusField = "i_nik";
               Proceed = 0;
               break;
            }
			if((!n_nama.value)){
               Message = "Data Nama tidak boleh kosong";
               FocusField = "n_nama";
               Proceed = 0;
               break;
            }
			if((!n_penyakit.value)){
               Message = "Nama Penyakit tidak boleh kosong";
               FocusField = "n_penyakit";
               Proceed = 0;
               break;
            }

            break;
         }
        
         if (Proceed == 1){ 
            return true;
         }else{
            alert( Message );
            eval(xstr+"."+FocusField+".focus();");           	
            return false;
         }
   }
}

var option_tambahdataluarnegri = {
	target:'#tableview',
	beforeSubmit: function() { 
					aobj= document.frmcatatluarnegri;
					astr= "document.frmcatatluarnegri";
					var cekVal = validasidataluarnegri(aobj,astr);
					if (cekVal == false) {
					return false;}
						}, 
	success:masukdataluarnegrisukses,
	url: '<?php echo $this->basePath; ?>/kp/dataluarnegri/maintaindata',
	type: 'post',
	resetForm: false
};

function masukdataluarnegrisukses() {
	location.href="#top";
	doCounter(5);
	$("#frmcatatluarnegri").ajaxForm(option_tambahdataluarnegri);
}

function validasidataluarnegri(xy,xstr)
{
	
   with(xy){
      
         var Proceed = 1;
         var Message;
         var FocusField;
         while (Proceed == 1)
         {

			if(!i_nik.value){
               Message = "Data Nip Dari tidak boleh kosong";
               FocusField = "i_nik";
               Proceed = 0;
               break;
            }
			if((!n_nama.value)){
               Message = "Data Nama tidak boleh kosong";
               FocusField = "n_nama";
               Proceed = 0;
               break;
            }
			if((!n_pd_negara.value)){
               Message = "negara tujuan tidak boleh kosong";
               FocusField = "n_pd_negara";
               Proceed = 0;
               break;
            }

            break;
         }
        
         if (Proceed == 1){ 
            return true;
         }else{
            alert( Message );
            eval(xstr+"."+FocusField+".focus();");           	
            return false;
         }
   }
}

var option_tambahdataorangtua = {
	target:'#tableview',
	beforeSubmit: function() { 
					aobj= document.frmcatatorangtua;
					astr= "document.frmcatatorangtua";
					var cekVal = validasidataorangtua(aobj,astr);
					if (cekVal == false) {
					return false;}
						}, 
	success:masukdataorangtuasukses,
	url: '<?php echo $this->basePath; ?>/kp/dataorangtua/maintaindata',
	type: 'post',
	resetForm: false
};

function masukdataorangtuasukses() {
	location.href="#top";
	doCounter(5);
	$("#frmcatatorangtua").ajaxForm(option_tambahdataorangtua);
}

function validasidataorangtua(xy,xstr)
{
	
   with(xy){
      
         var Proceed = 1;
         var Message;
         var FocusField;
         while (Proceed == 1)
         {

			if(!i_nik.value){
               Message = "Data Nip Dari tidak boleh kosong";
               FocusField = "i_nik";
               Proceed = 0;
               break;
            }
			if((!c_keluarga.value)){
               Message = "Hubungan Keluarga tidak boleh kosong";
               FocusField = "c_keluarga";
               Proceed = 0;
               break;
            }
			if((!n_namakeluarga.value)){
               Message = "Nama Keluarga tidak boleh kosong";
               FocusField = "n_namakeluarga";
               Proceed = 0;
               break;
            }			
            break;
         }
        
         if (Proceed == 1){ 
            return true;
         }else{
            alert( Message );
            eval(xstr+"."+FocusField+".focus();");           	
            return false;
         }
   }
}


var option_tambahdataorganisasi = {
	target:'#tableview',
	beforeSubmit: function() { 
					aobj= document.frmcatatorganisasi;
					astr= "document.frmcatatorganisasi";
					var cekVal = validasidataorganisasi(aobj,astr);
					if (cekVal == false) {
					return false;}
						}, 
	success:masukdataorganisasisukses,
	url: '<?php echo $this->basePath; ?>/kp/dataorganisasi/maintaindata',
	type: 'post',
	resetForm: false
};

function masukdataorganisasisukses() {
	location.href="#top";
	doCounter(5);
	$("#frmcatatorganisasi").ajaxForm(option_tambahdataorganisasi);
}

function validasidataorganisasi(xy,xstr)
{
	
   with(xy){
      
         var Proceed = 1;
         var Message;
         var FocusField;
         while (Proceed == 1)
         {

			if(!i_nik.value){
               Message = "Data Nip Dari tidak boleh kosong";
               FocusField = "i_nik";
               Proceed = 0;
               break;
            }
			if((!n_nama.value)){
               Message = "Data Nama tidak boleh kosong";
               FocusField = "n_nama";
               Proceed = 0;
               break;
            }
			if((!n_org_massa.value)){
               Message = "Nama organisasi tidak boleh kosong";
               FocusField = "n_org_massa";
               Proceed = 0;
               break;
            }			


            break;
         }
        
         if (Proceed == 1){ 
            return true;
         }else{
            alert( Message );
            eval(xstr+"."+FocusField+".focus();");           	
            return false;
         }
   }
}


var option_tambahdatapangkat = {
	target:'#tableview',
	beforeSubmit: function() { 
					aobj= document.frmcatatpangkat;
					astr= "document.frmcatatpangkat";
					var cekVal = validasidatapangkat(aobj,astr);
					if (cekVal == false) {
					return false;}
						}, 
	success:masukdatapangkatsukses,
	url: '<?php echo $this->basePath; ?>/kp/datapangkat/maintaindata',
	type: 'post',
	resetForm: false
};

function masukdatapangkatsukses() {
	location.href="#top";
	doCounter(5);
	$("#frmcatatpangkat").ajaxForm(option_tambahdatapangkat);
}

function validasidatapangkat(xy,xstr)
{
	
   with(xy){
      
         var Proceed = 1;
         var Message;
         var FocusField;
         while (Proceed == 1)
         {

			if(!i_nik.value){
               Message = "Data Nip Dari tidak boleh kosong";
               FocusField = "i_nik";
               Proceed = 0;
               break;
            }
			if((!n_nama.value)){
               Message = "Data Nama tidak boleh kosong";
               FocusField = "n_nama";
               Proceed = 0;
               break;
            }
			if((!c_golongan.value)){
               Message = "Golongan tidak boleh kosong";
               FocusField = "c_golongan";
               Proceed = 0;
               break;
            }

            break;
         }
        
         if (Proceed == 1){ 
            return true;
         }else{
            alert( Message );
            eval(xstr+"."+FocusField+".focus();");           	
            return false;
         }
   }
}

var option_tambahdatapelatihan = {
	target:'#tableview',
	beforeSubmit: function() { 
					aobj= document.frmcatatpelatihan;
					astr= "document.frmcatatpelatihan";
					var cekVal = validasidatapelatihan(aobj,astr);
					if (cekVal == false) {
					return false;}
						}, 
	success:masukdatapelatihansukses,
	url: '<?php echo $this->basePath; ?>/kp/datapelatihan/maintaindata',
	type: 'post',
	resetForm: false
};

function masukdatapelatihansukses() {
	location.href="#top";
	doCounter(5);
	$("#frmcatatpelatihan").ajaxForm(option_tambahdatapelatihan);
}

function validasidatapelatihan(xy,xstr)
{
	
   with(xy){
      
         var Proceed = 1;
         var Message;
         var FocusField;
         while (Proceed == 1)
         {

			if(!i_nik.value){
               Message = "Data Nip Dari tidak boleh kosong";
               FocusField = "i_nik";
               Proceed = 0;
               break;
            }
			if((!n_nama.value)){
               Message = "Data Nama tidak boleh kosong";
               FocusField = "n_nama";
               Proceed = 0;
               break;
            }
			if((!n_pelatihan.value)){
               Message = "Nama pelatihan tidak boleh kosong";
               FocusField = "n_pelatihan";
               Proceed = 0;
               break;
            }

            break;
         }
        
         if (Proceed == 1){ 
            return true;
         }else{
            alert( Message );
            eval(xstr+"."+FocusField+".focus();");           	
            return false;
         }
   }
}


var option_tambahdatapendidikan = {
	target:'#tableview',
	beforeSubmit: function() { 
					aobj= document.frmcatatpendidikan;
					astr= "document.frmcatatpendidikan";
					var cekVal = validasidatapendidikan(aobj,astr);
					if (cekVal == false) {
					return false;}
						}, 
	success:masukdatapendidikansukses,
	url: '<?php echo $this->basePath; ?>/kp/datapendidikan/maintaindata',
	type: 'post',
	resetForm: false
};

function masukdatapendidikansukses() {
	location.href="#top";
	doCounter(5);
	$("#frmcatatpendidikan").ajaxForm(option_tambahdatapendidikan);
}

function validasidatapendidikan(xy,xstr)
{
	
   with(xy){
      
         var Proceed = 1;
         var Message;
         var FocusField;
         while (Proceed == 1)
         {

			if(!i_nik.value){
               Message = "Data Nip Dari tidak boleh kosong";
               FocusField = "i_nik";
               Proceed = 0;
               break;
            }
			if((!n_nama.value)){
               Message = "Data Nama tidak boleh kosong";
               FocusField = "n_nama";
               Proceed = 0;
               break;
            }
			if((!c_pendidikan.value)){
               Message = "Jenjang Pendidikan tidak boleh kosong";
               FocusField = "c_pendidikan";
               Proceed = 0;
               break;
            }

            break;
         }
        
         if (Proceed == 1){ 
            return true;
         }else{
            alert( Message );
            eval(xstr+"."+FocusField+".focus();");           	
            return false;
         }
   }
}

var option_tambahdatapenelitian = {
	target:'#tableview',
	beforeSubmit: function() { 
					aobj= document.frmcatatpenelitian;
					astr= "document.frmcatatpenelitian";
					var cekVal = validasidatapenelitian(aobj,astr);
					if (cekVal == false) {
					return false;}
						}, 
	success:masukdatapenelitiansukses,
	url: '<?php echo $this->basePath; ?>/kp/datapenelitian/maintaindata',
	type: 'post',
	resetForm: false
};

function masukdatapenelitiansukses() {
	location.href="#top";
	doCounter(5);
	$("#frmcatatpenelitian").ajaxForm(option_tambahdatapenelitian);
}

function validasidatapenelitian(xy,xstr)
{
	
   with(xy){
      
         var Proceed = 1;
         var Message;
         var FocusField;
         while (Proceed == 1)
         {

			if(!i_nik.value){
               Message = "Data Nip Dari tidak boleh kosong";
               FocusField = "i_nik";
               Proceed = 0;
               break;
            }
			if((!n_nama.value)){
               Message = "Data Nama tidak boleh kosong";
               FocusField = "n_nama";
               Proceed = 0;
               break;
            }
			if((!e_judul.value)){
               Message = "judul penelitian tidak boleh kosong";
               FocusField = "e_judul";
               Proceed = 0;
               break;
            }

            break;
         }
        
         if (Proceed == 1){ 
            return true;
         }else{
            alert( Message );
            eval(xstr+"."+FocusField+".focus();");           	
            return false;
         }
   }
}


var option_tambahdatapenghargaan = {
	target:'#tableview',
	beforeSubmit: function() { 
					aobj= document.frmcatatpenghargaan;
					astr= "document.frmcatatpenghargaan";
					var cekVal = validasidatapenghargaan(aobj,astr);
					if (cekVal == false) {
					return false;}
						}, 
	success:masukdatapenghargaansukses,
	url: '<?php echo $this->basePath; ?>/kp/datapenghargaan/maintaindata',
	type: 'post',
	resetForm: false
};

function masukdatapenghargaansukses() {
	location.href="#top";
	doCounter(5);
	$("#frmcatatpenghargaan").ajaxForm(option_tambahdatapenghargaan);
}

function validasidatapenghargaan(xy,xstr)
{
	
   with(xy){
      
         var Proceed = 1;
         var Message;
         var FocusField;
         while (Proceed == 1)
         {

			if(!i_nik.value){
               Message = "Data Nip Dari tidak boleh kosong";
               FocusField = "i_nik";
               Proceed = 0;
               break;
            }
			if((!n_nama.value)){
               Message = "Data Nama tidak boleh kosong";
               FocusField = "n_nama";
               Proceed = 0;
               break;
            }
			if((!i_srt_penghargaan.value)){
               Message = "Nomor penghargaan tidak boleh kosong";
               FocusField = "i_srt_penghargaan";
               Proceed = 0;
               break;
            }

            break;
         }
        
         if (Proceed == 1){ 
            return true;
         }else{
            alert( Message );
            eval(xstr+"."+FocusField+".focus();");           	
            return false;
         }
   }
}


var option_tambahdatapenugasan = {
	target:'#tableview',
	beforeSubmit: function() { 
					aobj= document.frmcatatpenugasan;
					astr= "document.frmcatatpenugasan";
					var cekVal = validasidatapenugasan(aobj,astr);
					if (cekVal == false) {
					return false;}
						}, 
	success:masukdatapenugasansukses,
	url: '<?php echo $this->basePath; ?>/kp/datapenugasan/maintaindata',
	type: 'post',
	resetForm: false
};

function masukdatapenugasansukses() {
	location.href="#top";
	doCounter(5);
	$("#frmcatatpenugasan").ajaxForm(option_tambahdatapenugasan);
}

function validasidatapenugasan(xy,xstr)
{
	
   with(xy){
      
         var Proceed = 1;
         var Message;
         var FocusField;
         while (Proceed == 1)
         {

			if(!i_nik.value){
               Message = "Data Nip Dari tidak boleh kosong";
               FocusField = "i_nik";
               Proceed = 0;
               break;
            }
			if((!n_nama.value)){
               Message = "Data Nama tidak boleh kosong";
               FocusField = "n_nama";
               Proceed = 0;
               break;
            }
			if((!n_penugasan.value)){
               Message = "Penugasan tidak boleh kosong";
               FocusField = "n_penugasan";
               Proceed = 0;
               break;
            }

            break;
         }
        
         if (Proceed == 1){ 
            return true;
         }else{
            alert( Message );
            eval(xstr+"."+FocusField+".focus();");           	
            return false;
         }
   }
}

var option_tambahdataphoto = {
	target:'#tableview',
	beforeSubmit: function() { 
					aobj= document.frmcatatphoto;
					astr= "document.frmcatatphoto";
					var cekVal = validasidataphoto(aobj,astr);
					if (cekVal == false) {
					return false;}
						}, 
	success:masukdataphotosukses,
	url: '<?php echo $this->basePath; ?>/kp/dataphoto/maintaindata',
	type: 'post',
	resetForm: false
};

function masukdataphotosukses() {
	location.href="#top";
	doCounter(5);
	$("#frmcatatphoto").ajaxForm(option_tambahdataphoto);
}

function validasidataphoto(xy,xstr)
{
	
   with(xy){
      
         var Proceed = 1;
         var Message;
         var FocusField;
         while (Proceed == 1)
         {

			if(!i_nik.value){
               Message = "Data Nip Dari tidak boleh kosong";
               FocusField = "i_nik";
               Proceed = 0;
               break;
            }
			if((!n_nama.value)){
               Message = "Data Nama tidak boleh kosong";
               FocusField = "n_nama";
               Proceed = 0;
               break;
            }
			if((!a_photofile.value)){
               Message = "File tidak boleh kosong";
               FocusField = "a_photofile";
               Proceed = 0;
               break;
            }

            break;
         }
        
         if (Proceed == 1){ 
            return true;
         }else{
            alert( Message );
            eval(xstr+"."+FocusField+".focus();");           	
            return false;
         }
   }
}

var option_tambahdatapublikasi = {
	target:'#tableview',
	beforeSubmit: function() { 
					aobj= document.frmcatatpublikasi;
					astr= "document.frmcatatpublikasi";
					var cekVal = validasidatapublikasi(aobj,astr);
					if (cekVal == false) {
					return false;}
						}, 
	success:masukdatapublikasisukses,
	url: '<?php echo $this->basePath; ?>/kp/datapublikasi/maintaindata',
	type: 'post',
	resetForm: false
};

function masukdatapublikasisukses() {
	location.href="#top";
	doCounter(5);
	$("#frmcatatpublikasi").ajaxForm(option_tambahdatapublikasi);
}

function validasidatapublikasi(xy,xstr)
{
	
   with(xy){
      
         var Proceed = 1;
         var Message;
         var FocusField;
         while (Proceed == 1)
         {

			if(!i_nik.value){
               Message = "Data Nip Dari tidak boleh kosong";
               FocusField = "i_nik";
               Proceed = 0;
               break;
            }
			if((!n_nama.value)){
               Message = "Data Nama tidak boleh kosong";
               FocusField = "n_nama";
               Proceed = 0;
               break;
            }
			if((!e_judul.value)){
               Message = "judul publikasi tidak boleh kosong";
               FocusField = "e_judul";
               Proceed = 0;
               break;
            }

            break;
         }
        
         if (Proceed == 1){ 
            return true;
         }else{
            alert( Message );
            eval(xstr+"."+FocusField+".focus();");           	
            return false;
         }
   }
}


var option_tambahdatasanksi = {
	target:'#tableview',
	beforeSubmit: function() { 
					aobj= document.frmcatatsanksi;
					astr= "document.frmcatatsanksi";
					var cekVal = validasidatasanksi(aobj,astr);
					if (cekVal == false) {
					return false;}
						}, 
	success:masukdatasanksisukses,
	url: '<?php echo $this->basePath; ?>/kp/datasanksi/maintaindata',
	type: 'post',
	resetForm: false
};

function masukdatasanksisukses() {
	location.href="#top";
	doCounter(5);
	$("#frmcatatsanksi").ajaxForm(option_tambahdatasanksi);
}

function validasidatasanksi(xy,xstr)
{
	
   with(xy){
      
         var Proceed = 1;
         var Message;
         var FocusField;
         while (Proceed == 1)
         {

			if(!i_nik.value){
               Message = "Data Nip Dari tidak boleh kosong";
               FocusField = "i_nik";
               Proceed = 0;
               break;
            }
			if((!n_nama.value)){
               Message = "Data Nama tidak boleh kosong";
               FocusField = "n_nama";
               Proceed = 0;
               break;
            }
			if((!c_hukuman.value)){
               Message = "Jenis Hukuman tidak boleh kosong";
               FocusField = "c_hukuman";
               Proceed = 0;
               break;
            }
			if((!i_hukuman.value)){
               Message = "Nomor Hukuman tidak boleh kosong";
               FocusField = "i_hukuman";
               Proceed = 0;
               break;
            }
            break;
         }
        
         if (Proceed == 1){ 
            return true;
         }else{
            alert( Message );
            eval(xstr+"."+FocusField+".focus();");           	
            return false;
         }
   }
}

var option_tambahdataseminar = {
	target:'#tableview',
	beforeSubmit: function() { 
					aobj= document.frmcatatseminar;
					astr= "document.frmcatatseminar";
					var cekVal = validasidataseminar(aobj,astr);
					if (cekVal == false) {
					return false;}
						}, 
	success:masukdataseminarsukses,
	url: '<?php echo $this->basePath; ?>/kp/dataseminar/maintaindata',
	type: 'post',
	resetForm: false
};

function masukdataseminarsukses() {
	location.href="#top";
	doCounter(5);
	$("#frmcatatseminar").ajaxForm(option_tambahdataseminar);
}

function validasidataseminar(xy,xstr)
{
	
   with(xy){
      
         var Proceed = 1;
         var Message;
         var FocusField;
         while (Proceed == 1)
         {

			if(!i_nik.value){
               Message = "Data Nip Dari tidak boleh kosong";
               FocusField = "i_nik";
               Proceed = 0;
               break;
            }
			if((!n_nama.value)){
               Message = "Data Nama tidak boleh kosong";
               FocusField = "n_nama";
               Proceed = 0;
               break;
            }
			if((!n_seminar.value)){
               Message = "Nama seminar tidak boleh kosong";
               FocusField = "n_seminar";
               Proceed = 0;
               break;
            }			
			if((!n_seminar_peran.value)){
               Message = "Peran seminar tidak boleh kosong";
               FocusField = "n_seminar_peran";
               Proceed = 0;
               break;
            }

            break;
         }
        
         if (Proceed == 1){ 
            return true;
         }else{
            alert( Message );
            eval(xstr+"."+FocusField+".focus();");           	
            return false;
         }
   }
}


var option_tambahdatasertifikasi = {
	target:'#tableview',
	beforeSubmit: function() { 
					aobj= document.frmcatatsertifikat;
					astr= "document.frmcatatsertifikat";
					var cekVal = validasidatasertifikasi(aobj,astr);
					if (cekVal == false) {
					return false;}
						}, 
	success:masukdatasertifikasisukses,
	url: '<?php echo $this->basePath; ?>/kp/datasertifikasi/maintaindata',
	type: 'post',
	resetForm: false
};

function masukdatasertifikasisukses() {
	location.href="#top";
	doCounter(5);
	$("#frmcatatsertifikat").ajaxForm(option_tambahdatasertifikasi);
}

function validasidatasertifikasi(xy,xstr)
{
	
   with(xy){
      
         var Proceed = 1;
         var Message;
         var FocusField;
         while (Proceed == 1)
         {

			if(!i_nik.value){
               Message = "Data Nip Dari tidak boleh kosong";
               FocusField = "i_nik";
               Proceed = 0;
               break;
            }
			if((!n_nama.value)){
               Message = "Data Nama tidak boleh kosong";
               FocusField = "n_nama";
               Proceed = 0;
               break;
            }
			if((!n_sertifikat.value)){
               Message = "Nama sertifikat tidak boleh kosong";
               FocusField = "n_sertifikat";
               Proceed = 0;
               break;
            }			
			if((!i_sertifikat.value)){
               Message = "Nomor sertifikat tidak boleh kosong";
               FocusField = "i_sertifikat";
               Proceed = 0;
               break;
            }

            break;
         }
        
         if (Proceed == 1){ 
            return true;
         }else{
            alert( Message );
            eval(xstr+"."+FocusField+".focus();");           	
            return false;
         }
   }
}



</script>
<fieldset class="fields">
<table width="100%" border="0" cellpadding="3" cellspacing="0" align="center">
	<tr>
		<td>
			<a href="#" onclick="cariDataPribadi('<?=$i_nik?>','<?=$n_nama?>');">Data Pribadi</a> |
			<a href="#" onclick="cariPendidikan('<?=$i_nik?>','<?=$n_nama?>');">Pendidikan Formal</a> |
			<a href="#" onclick="cariGolonganPangkat('<?=$i_nik?>','<?=$n_nama?>');">Golongan Pangkat</a> |			
			<a href="#" onclick="cariPangkatAkademik('<?=$i_nik?>','<?=$n_nama?>');">Riwayat Pangkat Akedemik</a> |			
			<a href="#" onclick="cariRiwayatPekerjaan('<?=$i_nik?>','<?=$n_nama?>');">Riwayat Pekerjaan</a> |			
			<a href="#" onclick="cariPelatihan('<?=$i_nik?>','<?=$n_nama?>');">Pelatihan</a> |
			<a href="#" onclick="cariSeminar('<?=$i_nik?>','<?=$n_nama?>');">Seminar</a> |			
			<a href="#" onclick="cariPenelitian('<?=$i_nik?>','<?=$n_nama?>');">Penelitian</a> |
			<a href="#" onclick="cariPublikasi('<?=$i_nik?>','<?=$n_nama?>');">Publikasi</a> |
			<a href="#" onclick="cariLuarNegri('<?=$i_nik?>','<?=$n_nama?>');">Ke Luar Negeri</a> |
		</td>
	</tr>
	<tr>
		<td>
			<a href="#" onclick="cariPenugasan('<?=$i_nik?>','<?=$n_nama?>');">Penugasan</a> |
			<a href="#" onclick="cariSertifikasi('<?=$i_nik?>','<?=$n_nama?>');">Sertifikasi</a> |
			<a href="#" onclick="cariKeluarga('<?=$i_nik?>','<?=$n_nama?>');">Keluarga</a> |
			<a href="#" onclick="cariOrtu('<?=$i_nik?>','<?=$n_nama?>');">Orang Tua dan Kerabat</a> |
			<a href="#" onclick="cariAlamat('<?=$i_nik?>','<?=$n_nama?>');">Riwayat Alamat</a> |
			<a href="#" onclick="cariSanksi('<?=$i_nik?>','<?=$n_nama?>');">Sanksi/Hukuman</a> |
			<a href="#" onclick="cariOrganisasi('<?=$i_nik?>','<?=$n_nama?>');">Organisasi</a> |
			<a href="#" onclick="cariPenghargaan('<?=$i_nik?>','<?=$n_nama?>');">Penghargaan</a> |
			<a href="#" onclick="cariCiriFisik('<?=$i_nik?>','<?=$n_nama?>');">Ciri-ciri Fisik</a> |
			<a href="#" onclick="cariDataKesehatan('<?=$i_nik?>','<?=$n_nama?>');">Riwayat Kesehatan</a> |
			<a href="#" onclick="cariPhoto('<?=$i_nik?>','<?=$n_nama?>');">Ambil Foto</a>
		</td>
	</tr>	
</table>
<br>


</fieldset>	


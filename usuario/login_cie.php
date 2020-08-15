<script>
function eliminamensaje(){

	 $("#error").removeClass("vVisible").addClass("vOculto");
}

function login(){
	try{
		var xmlhttp;
		xmlhttp=new XMLHttpRequest();
		xmlhttp.onreadystatechange=function() {
			if(xmlhttp.readyState==4 && xmlhttp.status==200){
				if(trim(xmlhttp.responseText)=="habilitado"){
					window.alert("Por favor verifique el estado de su habilitación para el año en curso");
				}else if(xmlhttp.responseText.substring(0,2)=="ok"){ 
				    location.reload("index.php");
				}else{
					if(trim(xmlhttp.responseText) == "dia"){
						document.getElementById('error').innerHTML = "No tiene acceso a la pagina. Tus dias laborales no coinciden.";
						$("#error").removeClass("vOculto").addClass("vVisible");
					} else if(trim(xmlhttp.responseText) == "hora"){
						document.getElementById('error').innerHTML = "No tiene acceso a la pagina. Tu horario de trabajo no coinciden.";
						$("#error").removeClass("vOculto").addClass("vVisible");
					} else if (trim(xmlhttp.responseText) == "faltausuario") {
						document.getElementById('error').innerHTML = "Debe completar los campos de ingreso.";
					    $("#error").removeClass("vOculto").addClass("vVisible");
					}else if(trim(xmlhttp.responseText) == "vacaciones"){
						console.log(xmlhttp.responseText);
						document.getElementById('error').innerHTML = "Usted esta de Vacaciones.";
					    $("#error").removeClass("vOculto").addClass("vVisible");
					}else{
						document.getElementById('error').innerHTML = "Usuario o contraseña incorrectos!";
						$("#error").removeClass("vOculto").addClass("vVisible");
					}
				}
			}
		}
		var txtUsuario=document.getElementById("userLogin");
		var txtPass=document.getElementById("passLogin");
		if(txtUsuario.value == ""){
			document.getElementById('error').innerHTML = "Debe completar los campos de ingreso.";
			$("#error").removeClass("vOculto").addClass("vVisible");
		}
		if(txtPass.value == ""){
			document.getElementById('error').innerHTML = "Debe completar los campos de ingreso.";
			$("#error").removeClass("vOculto").addClass("vVisible");
		}
		
		if(txtUsuario.value.length>0){
			var params="username="+txtUsuario.value+"&password="+txtPass.value;
			var randomnumber=Math.floor(Math.random()*301);
			params+="&rand="+randomnumber;
			xmlhttp.open("POST","usuario/validar.php",true);
			xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xmlhttp.send(params);
			txtPass.value="";
			txtUsuario.value="";
		}
	}catch (exception){
		window.alert("Error="+exception);
	}
}

function focoUsuario(){
	$('#userLogin').focus();
}

function trim (myString)
{
    return myString.replace(/^\s+/g,'').replace(/\s+$/g,'');
}
</script>
<form action="javascript:void(0);" onSubmit="login()">
	<div style="width:300px;margin:7% auto;text-align:center;">
      	<div style="font-family:'Source Sans Pro','Helvetica Neue', 'Helvetica';font-size:35px;color:#43CCEB;">
        <b>CIE</b> Tickets
      	</div>
      	<div style="background-color:white;padding:30px;">
        	<p class="login-box-msg">Inicia sesi&oacute;n</p>
          	<div class="form-group has-feedback">
            	<input id="userLogin" type="text" class="form-control campousuario"  placeholder="Usuario" onclick="eliminamensaje();" />
            	<span class="glyphicon glyphicon-user form-control-feedback"></span>
          	</div>
          	<div class="form-group has-feedback">
            	<input id="passLogin" type="password" class="form-control" placeholder="Clave" onclick="eliminamensaje();"/>
            	<span class="glyphicon glyphicon-lock form-control-feedback"></span>
          	</div>
            <div class="col-xs-4">
              	<button type="submit" class="btn btn-primary btn-block btn-flat">Entrar</button>
            </div>    
      	</div>
    </div>
</form>
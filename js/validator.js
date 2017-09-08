var Validator = function(options){
	var $formulario = options.form,
		rules = options.rules,
		$errorDiv = $($formulario, '.error') || $($formulario, '.status') || $($formulario, '.server-error'),
		errores = [],
		i,
		prop,
		fnRegla,
		nombreRegla,
		valorRegla;

	$errorDiv.innerHTML = '';

	for(prop in rules) {
		for(i=0 ; i<rules[prop].length ; i++){
			var regla = rules[prop][i],
				rta;
			if(regla.indexOf(':') !== -1) {
				var datosRegla = regla.split(':');
				nombreRegla = '_' + datosRegla[0];
				valorRegla = datosRegla[1];
				fnRegla = window[nombreRegla];

				if(typeof fnRegla === "function") {
					rta = fnRegla.call(null, prop, $formulario[prop].value, valorRegla);
					if (rta.exito == false) {
						errores.push(rta.msg);
						break;
					}
				} else {
					console.log("No existe la funcion " + nombreRegla);
				}
			} else {
				if ($formulario[prop].length > 1) {
					nombreRegla = "_" + regla + "Radio";
				} else {
					nombreRegla = "_" + regla;
				}
				
				fnRegla = window[nombreRegla];

				if(typeof fnRegla === "function") {
					if ($formulario[prop].length > 1) {
						rta = fnRegla.call(null, prop, $formulario[prop]);
					} else {
						rta = fnRegla.call(null, prop, $formulario[prop].value);
					}
					if (rta.exito == false) {
						errores.push(rta.msg);
						break;
					}
				} else {
					console.log("No existe la funcion " + nombreRegla);
				}
			}
		}
	}

	if (errores.length > 0) {
		for (i=0;i<errores.length;i++) {
			$errorDiv.innerHTML += errores[i];
		}
	} else {
		options.submitSuccess();
	}

};

var patrones = {
    nombreApellido: /^[a-zA-Z ]{3,20}$/,
    email: /^[a-z0-9\._]{3,}@[a-z]+\.[a-z]{2,6}(\.[a-z]{2})?$/,
    password: /^[a-zA-Z0-9\*\.\_\-\$\#]{6,20}$/,
    foto: /(jpeg|gif|png)$/,
    descripcion: /^[a-zA-Z ]{5,}$/,
    username: /^[a-zA-Z0-9]{3,20}$/
};

/***** METODOS DE VALIDACION *****/

function _required(campo,valor) {
	var output = {exito:false};
	if (valor == "") {
		output.msg = "El "+campo+" no puede estar vacío.<br />";
	} else {
		output.exito = true;
	}
	return output;
}

function _requiredRadio(nombreCampo,campo) {
	var output = {exito:false},
		i,
		error = 0;
	for (i = 0; i < campo.length; i++) {
    	if (campo[i].checked === true) {
    		error++;
    	}
    }
    if (error == campo.length) {
        output.msg = 'Elija '+ nombreCampo +', por favor.<br />';
    } else {
    	output.exito = true;
    }	
	return output;
}

function _minlength(campo,valor,longitud){
	var output = {exito:false};
	if(valor.length < longitud) {
		output.msg = "El "+campo+" debe tener al menos "+longitud+" caracteres.<br />";
	} else {
		output.exito = true;
	}
	return output;
}

function _maxlength(campo,valor,longitud){
	var output = {exito:false};
	if(valor.length > longitud) {
		output.msg = "El "+campo+" debe tener como maximo "+longitud+" caracteres.<br />";
	} else {
		output.exito = true;
	}
	return output;
}

function _equals(campo,valor,campoVerificacion){
	var output = {exito:false};
	if(valor !== campoVerificacion) {
		output.msg = campo+" no coincide con "+campoVerificacion+".<br />";
	} else {
		output.exito = true;
	}
	return output;
}

function _numeric(campo,valor){
	var output = {exito:false};
	if(isNaN(valor)) {
		output.msg = "El "+campo+" debe ser un número.<br />";
	} else {
		output.exito = true;
	}
	return output;
}

function _nombreApellido(campo,valor){
	var output = {exito:false};
	if(!valor.match(patrones.nombreApellido)) {
		output.msg = "El "+campo+" no es valido, debe contener solo letras.<br />";
	} else {
		output.exito = true;
	}
	return output;
}

function _email(campo,valor){
	var output = {exito:false};
	if(!valor.match(patrones.email)) {
		output.msg = "El "+campo+" no es valido, debe tener formato de email(example@server.com).<br />";
	} else {
		output.exito = true;
	}
	return output;
}

function _password(campo,valor){
	var output = {exito:false};
	if(!valor.match(patrones.password)) {
		output.msg = "El "+campo+" es invalido, caracteres validos: a-z 0-9 - _ $ * . # <br />";
	} else {
		output.exito = true;
	}
	return output;
}

function _foto(campo,valor){
	var output = {exito:false};
	if(!valor.match(patrones.foto)) {
		output.msg = "El "+campo+" no es valido, tiene que ser un jpg, png o gif.<br />";
	} else {
		output.exito = true;
	}
	return output;
}

function _descripcion(campo,valor){
	var output = {exito:false};
	if(!valor.match(patrones.descripcion)) {
		output.msg = "El "+campo+" no es valido, solo puede contener letras y espacios.<br />";
	} else {
		output.exito = true;
	}
	return output;
}

function _username(campo,valor){
	var output = {exito:false};
	if(!valor.match(patrones.username)) {
		output.msg = "El "+campo+" no es valido, no puede contener caracteres especiales.<br />";
	} else {
		output.exito = true;
	}
	return output;
}
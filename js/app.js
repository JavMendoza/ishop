var App = {
	init: function(){
		var $main = document.getElementsByTagName("main")[0],
			$boton_logo = $$$('logo'),
			$boton_home_link = $$$("home"),
			i;

		var volverHome = function() {
			var page_index = $($main,".page-index");
			var pages = $$($main,"section");
			for (i = 0; i < pages.length; i++) {
				pages[i].removeClass("active");
			}
			page_index.className += " active";
		};

		$boton_logo.addEventListener('click', function(e) {
			e.preventDefault();
			volverHome();
		}, false);

		$boton_home_link.addEventListener('click', function(e) {
			e.preventDefault();
			volverHome();
		}, false);

		App.Components.LeftNavigation.init($main);
		App.Components.ProductListing.init($main);
		App.Components.CreateAccount.init($main);
		App.Components.Login.init($main);
	},
	Components: {
		ProductListing: {
			init: function($main) {
				var self = this;
				self.traer(1, $main);
			},
			traer: function(id, $main, callback) {
				ajax({
					url: 'api/get-productos.php',
					method: 'get',
					data: 'id_categoria='+ ((id != false) ? id : 'false'),
					success: function(rta) {
						var $product_listing = $($main,".product-listing");
						if (rta.length > 0) {
							var $ul_created = $($product_listing,"ul");
							var $p_created = $($product_listing,"p.no-records");
							if ($ul_created != null) {
								remove($product_listing,$ul_created);
							}
							if ($p_created != null) {
								remove($product_listing,$p_created);
							}
							var ul = create("ul");
							for (var i = 0; i < rta.length; i++) {
								var product = rta[i];

								var li = create("li");
								li.className = "product";

								var img = create("img");
								img.alt = product.nombre;
								img.src = "cms/imagenes/productos/"+product.imagen;

								var h3 = create("h3");
								h3.innerHTML = product.nombre;

								var p = create("p");
								p.innerHTML = product.descripcion;

								var span = create("span");
								span.innerHTML = "Precio:";

								var precio = create("p");
								precio.innerHTML = product.precio;

								append(li,img);
								append(li,h3);
								append(li,p);
								append(li,span);
								append(li,precio);
								append(ul,li);
							}

							append($product_listing,ul);
						} else {
							var $ul_created = $($product_listing,"ul");
							if ($ul_created != null) {
								remove($product_listing,$ul_created);
							}

							var $p_created = $($product_listing,"p.no-records");
							if ($p_created == null) {
								var p = create("p");
								p.className = "no-records";
								p.innerHTML = "Lo sentimos todavia no hay productos en esta categoria.";
								append($product_listing,p);
							}
						}

						if (callback && callback != null) {
							callback();
						}
					},
					error: function(err) {
						console.log(err);
					}
				});
			}
		},
		LeftNavigation: {
			init: function($main) {
				var self = this,
					i;

				var ajaxFinished = function() {
					var $link_cat = $$($main,".categories ul li > a");
					for (i = 0; i < $link_cat.length; i++) {
	            		$link_cat[i].addEventListener('click', function(e) {
							e.preventDefault();
							var id_cat = this.href.split("=")[1];
							App.Components.ProductListing.traer(id_cat,$main);
						}, false);
	            	}
				}
				self.traer(false, $main, ajaxFinished);
			},
			traer: function(id, $main, callback) {
				ajax({
					url: 'api/get-categorias.php',
					method: 'get',
					data: 'id='+ ((id != false) ? id : 'false'),
					success: function(rta) {
						var $div_categories = $($main,".categories");
						var ul = create("ul");
						for (var i = 0; i < rta.length; i++) {
							var category = rta[i];
							var li = create("li");
							var link = create("a");
							link.href = "?cat="+category.id_categoria;
							link.innerHTML = category.nombre;
							append(li,link);
							append(ul,li);
						}

						append($div_categories,ul);
						if (callback && callback != null) {
							callback();
						}
					},
					error: function(err) {
						console.log(err);
					}
				});
			}
		},
		Login: {
			init: function($main) {
				var $formLogin = $$$('form-login'),
					$boton_login = $$$('login'),
					i,
					page_login = $($main,".page-login"),
					pages = $$($main,"section");

				$boton_login.addEventListener('click', function(e) {
					e.preventDefault();
					for (i = 0; i < pages.length; i++) {
						pages[i].removeClass("active");
					}
					page_login.className += " active";
				}, false);

				$formLogin.addEventListener('submit', function(e) {
					e.preventDefault();
					Validator({
						form: $formLogin,
						rules: {
							usuario: ['required', 'minlength:3', 'maxlength:20', 'username'],
							password: ['required', 'minlength:6', 'maxlength:20', 'password']
						},
						submitSuccess: function() {
							ajax({
								url: 'api/login-procesar.php',
								method: 'post',
								data: 'usuario=' + $formLogin.usuario.value +
										'&password=' + $formLogin.password.value,
								success: function(rta) {
									var $errorDiv = $($formLogin, '.error');
									if (rta.status == "error") {
										$errorDiv.innerHTML = '';
	                                    $errorDiv.innerHTML += rta.errors;
									} else if (rta.status == "success") {
										document.location = "cms/index.php";
									}
								},
								error: function(err) {
									console.log(err);
								}
							});
						}
					});
				}, false);
			}
		},
		CreateAccount: {
			init: function($main) {
				var self = this,
					$formRegistro = $$$('form-registro'),
					$formLogin = $$$('form-login'),
					$boton_registro = $$$('registro'),
					page_registro = $($main,".page-registro"),
					page_login = $($main,".page-login"),
					pages = $$($main,"section");

				$boton_registro.addEventListener('click', function(e) {
					e.preventDefault();
					for (i = 0; i < pages.length; i++) {
						pages[i].removeClass("active");
					}
					page_registro.className += " active";
				}, false);

				$formRegistro.addEventListener('submit', function(e) {
					e.preventDefault();
					Validator({
						form: $formRegistro,
						rules: {
							nombre: ['required', 'minlength:3', 'maxlength:20' ,'nombreApellido'],
							apellido: ['required', 'minlength:3', 'maxlength:20', 'nombreApellido'],					
							email: ['required', 'email'],
							sexo: ['required'],
							usuario: ['required', 'minlength:3', 'maxlength:20', 'username'],
							password: ['required', 'minlength:6', 'maxlength:20', 'password'],
							nivel: ['required']
						},
						submitSuccess: function() {
							var sexoElejido;
							for (i=0 ; i<$formRegistro.sexo.length ; i++) {
								if ($formRegistro.sexo[i].checked) {
									sexoElejido = $formRegistro.sexo[i].value;
								}
							}
							ajax({
								url: 'api/registro-procesar.php',
								method: 'post',
								data: 'nombre=' + $formRegistro.nombre.value + 
									  '&apellido=' + $formRegistro.apellido.value +
									  '&email=' + $formRegistro.email.value +
									  '&sexo=' + sexoElejido +
									  '&usuario=' + $formRegistro.usuario.value +
									  '&password=' + $formRegistro.password.value +
									  '&nivel=' + $formRegistro.nivel.value,
								success: function(rta) {
									var $errorDiv = $($formRegistro, '.error');
									if (rta.status == "error") {
										$errorDiv.innerHTML = '';
	                                    $errorDiv.innerHTML += rta.errors;
									} else if (rta.status == "success") {
										for (i = 0; i < pages.length; i++) {
											pages[i].removeClass("active");
										}
										page_login.className += " active";
									}
								},
								error: function(err) {
									console.log(err);
								}
							});
						}
					});
				}, false);
			}
		}
	}
};

window.addEventListener('DOMContentLoaded', function() {
	App.init();
}, false);
var CMS = {
	init: function() {
		var	$main = document.getElementsByTagName("main")[0], 
			$btn_mostrar_productos = $$$("mostrar-productos"),
			$btn_mostrar_categorias = $$$("mostrar-categorias"),
			$btn_mostrar_usuarios = $$$("mostrar-usuarios"),
			$navItems = $$($main,"li a"),
			pages = $$($main,"section"),
			$btn_agregar = $$($main,".btn-agregar"), 
			$save_sections = $$($main,".save-section"),
			i;

		
		for (i=0; i<$navItems.length;i++) {
			$navItems[i].addEventListener('click', function(e) {
				e.preventDefault();
				for (i = 0; i < pages.length; i++) {
					pages[i].removeClass("active");
				}
				var id_section = "page-"+this.getAttribute("data-id");
				var esta_seccion = $$$(id_section);
				esta_seccion.className += " active";
				var $section_interior = $$(esta_seccion,".section-interior");
				for (i=0; i<$section_interior.length;i++) {
					$section_interior[i].removeClass("active");
				}
				for (i=0; i<$navItems.length;i++) {
					$navItems[i].parentNode.removeClass("active");
				}
				$(esta_seccion,".listado-section").className += " active";
				this.parentNode.className += " active";
			}, false);
		}
		for (i=0; i<$btn_agregar.length;i++) {
			$btn_agregar[i].addEventListener('click', function(e) {
				e.preventDefault();
				this.parentNode.removeClass("active");
				this.parentNode.nextElementSibling.nextElementSibling.removeClass("active");
				var id_section_save = this.getAttribute("data-id");
				$$$(id_section_save).className += " active";
			}, false);
		}
		
		CMS.reInit($main);

		CMS.Components.Producto.init($main);
		CMS.Components.Categoria.init($main);
		CMS.Components.Usuario.init($main);
	},
	reInit: function($main){
		var $btn_delete = $$($main,".delete-button");
		for (i=0; i<$btn_delete.length;i++) {
			$btn_delete[i].addEventListener('click', function(e) {
				e.preventDefault();
				var titleArr = this.getAttribute("title").split(" ");
				var hrefArr = this.getAttribute("href").split("=");
				CMS.Components[titleArr[1]].delete(hrefArr[1]);
			}, false);
		}

		var $btn_cambiar = $$($main,".cambiar-button");
		for (i=0; i<$btn_cambiar.length;i++) {
			$btn_cambiar[i].addEventListener('click', function(e) {
				e.preventDefault();
				var titleArr = this.getAttribute("title").split(" "),
					hrefArr = this.getAttribute("href").split("="),
					$formEditar = $$$("form-editar-"+titleArr[1].toLowerCase().slice(0,3)),
					$section = $$$("page-"+titleArr[1].toLowerCase()+"s");
				
				CMS.Components[titleArr[1]].update(hrefArr[1]);
			}, false);
		}
	},
	Components: {
		removeStatus: function(statusDiv) {
			statusDiv.innerHTML = '';
			statusDiv.removeClass("bg-danger");
			statusDiv.removeClass("active");
			statusDiv.removeClass("bg-success");
		},
		traerListado: function(options) {
			var numColumns,
				nombreListado = options.serviceUrl.split("-")[1].split(".")[0];
			ajax({
				url: '../api/'+options.serviceUrl,
				method: 'get',
				success: function(rta) {
					if (rta.length > 0) {
						var $listado_section = $(options.section,".listado-section"),
							$table_content = $($listado_section,"table tbody"),
							numeroPosPropiedad,
							nombrePropiedad;

						if ($(options.section,"p.no-records") != null) {
							remove($listado_section,$(options.section,".listado-section p.no-records"));
						}
						
						var nameTable = options.serviceUrl.split(".")[0];

						if ($table_content == null) {
							var table = create("table"),
								thead = create("thead"),
								tbody = create("tbody");

							var tr_head = create("tr");
							for (numeroPosPropiedad in options.columns) {
								nombrePropiedad = options.columns[numeroPosPropiedad];
								var th = create("th");
								if (nombrePropiedad == "imagen") {
									nombrePropiedad = "foto";
								}
								if (nombrePropiedad.split("_")[0] == "id") {
									nombrePropiedad = "id";
								}
								th.innerHTML = capitalize(nombrePropiedad);
								append(tr_head,th);
							}
							var th_acciones = create("th");
							th_acciones.innerHTML = "Acciones";
							append(tr_head,th_acciones);

							append(thead,tr_head);
							append(table,thead);
							append(table,tbody);
							append($listado_section,table);
						} else {
							$table_content.innerHTML = "";
						}

						for (var i = 0; i < rta.length; i++) {
							var tr = create("tr");
							var item = rta[i];

							for (numeroPosPropiedad in options.columns) {
								var td = create("td");
								nombrePropiedad = options.columns[numeroPosPropiedad];
								if (nombrePropiedad == "imagen") {
									if (item.imagen != null && item.imagen != "") {
										var img = create("img");
										img.alt = item.nombre;
										img.src = "imagenes/"+nombreListado+"/"+item.imagen;
										append(td,img);
									} else {
										td.innerHTML = "No posee imagen.";
									}
								} else if(nombrePropiedad == "precio") {
									td.innerHTML = "$"+item[nombrePropiedad];
								} else {
									td.innerHTML = item[nombrePropiedad];
								}
								append(tr,td);
							}

							var td_acciones = create("td");
							var link_editar = create("a");
							link_editar.className = "cambiar-button";
							link_editar.title = "Editar "+capitalize(nombreListado.slice(0,-1));
							var nameForId = "id_"+nombreListado.slice(0,-1);
							link_editar.href = "../api/update-"+nombreListado+".php?id="+item[nameForId];
							link_editar.innerHTML = "EDITAR";

							append(td_acciones,link_editar);

							var link_delete = create("a");
							link_delete.className = "delete-button";
							link_delete.title = "Borrar "+capitalize(nombreListado.slice(0,-1));
							link_delete.href = "../api/delete-"+nombreListado+".php?id="+item[nameForId];
							link_delete.innerHTML = "BORRAR";

							append(td_acciones,link_delete);

							append(tr,td_acciones);

							append($($listado_section,"table tbody"),tr);
						}

						CMS.reInit($($listado_section,"table tbody"));
					} else {
						var table = $(options.section,".listado-section table");
						if (table != null) {
							remove($(options.section,".listado-section"), table);
						}
						var p = create("p");
						p.className = "no-records";
						p.innerHTML = "No hay "+nombreListado+" para mostrar";
						append($(options.section,".listado-section"),p);
						p = null;
					}
				},
				error: function(err, codErr) {
					console.log(err, codErr);
				}
			});
		},
		Producto: {
			init: function($main) {
				var self = this;
				var $formAltaProd = $$$('form-alta-prod');
				var $formEditarProd = $$$('form-editar-pro');
				$formAltaProd.addEventListener('submit', function(e) {
					e.preventDefault();
					var formData = new FormData(this);
					self.create(formData);
				}, false);

				$formEditarProd.addEventListener('submit', function(e) {
					e.preventDefault();
					ajax({
						url: '../api/update-productos.php',
						method: 'PUT',
						data: 'id=' + this.id.value + 
							  '&nombre=' + this.nombre.value + 
							  '&descripcion=' + this.descripcion.value + 
							  '&precio=' + this.precio.value + 
							  '&stock=' + this.stock.value +
							  '&id_categoria=' + this.id_categoria.value +
							  '&id_cat_sexo=' + this.id_cat_sexo.value,
						success: function(rta) {
							var status = $($$$("form-editar-pro"),'.status');
							if(rta.status == "SUCCESS" || rta.status == "success") {
								CMS.Components.removeStatus(status);

								if (rta.msg && rta.msg.length>0) {
									status.innerHTML = rta.msg;
								}
								status.className += " active bg-success";

								setTimeout(function(){
									CMS.Components.removeStatus(status);
									$$$("update-producto").removeClass("active");
									$$$("update-producto").previousElementSibling.previousElementSibling.className += " active";
								}, 3500);

								CMS.Components.traerListado({
									serviceUrl: "get-productos.php",
									section: $$$("page-productos"),
									columns: ["id_producto","imagen","nombre","descripcion","precio"]
								});
							} else {
								CMS.Components.removeStatus(status);

								if (typeof rta.errors == "object") {
									for (var error in rta.errors) {
										status.innerHTML += rta.errors[error];
									}
								} else {
									status.innerHTML += rta.errors;
								}
								status.className += " active bg-danger";
							}
						},
						error: function(err, codErr) {
							console.log(err, codErr);
						}
					});
				}, false);
			},
			create: function(formData) {
				ajax({
					url: '../api/save-productos.php',
					method: 'post',
					data: formData,
					success: function(rta) {
						var status = $($$$("form-alta-prod"),'.status');
						if(rta.status == "SUCCESS" || rta.status == "success") {
							console.log(rta.ultimoId);
							CMS.Components.removeStatus(status);

							if (rta.msg && rta.msg.length>0) {
								status.innerHTML = rta.msg;
							}
							status.className += " active bg-success";

							setTimeout(function(){
								CMS.Components.removeStatus(status);
								$$$("save-producto").removeClass("active");
								$$$("save-producto").previousElementSibling.className += " active";
							}, 3500);

							CMS.Components.traerListado({
								serviceUrl: "get-productos.php",
								section: $$$("page-productos"),
								columns: ["id_producto","imagen","nombre","descripcion","precio"]
							});
						} else {
							CMS.Components.removeStatus(status);

							if (typeof rta.errors == "object") {
								for (var error in rta.errors) {
									status.innerHTML += rta.errors[error];
								}
							} else {
								status.innerHTML += rta.errors;
							}
							status.className += " active bg-danger";
						}
					},
					error: function(err, codErr) {
						console.log(err, codErr);
					}
				});
			},
			update: function(id) {
				ajax({
					url: '../api/get-productos.php',
					method: 'get',
					data: 'id='+ ((id != false) ? id : 'false'),
					success: function(rta) {
						if(typeof rta == "object") {
							var $listado_section = $($$$("page-productos"),".listado-section"),
								$section_update = $$$("update-producto");
							
							$$$("form-editar-pro").id.value = rta.id_producto;
							$$$("form-editar-pro").nombre.value = rta.nombre;
							$$$("form-editar-pro").descripcion.value = rta.descripcion;
							$$$("form-editar-pro").precio.value = rta.precio;
							$$$("form-editar-pro").stock.value = rta.stock;
							
							$listado_section.removeClass("active");
							$section_update.className += " active";
						} else {
							console.log("no hay productos para mostrar");
						}
					},
					error: function(err, codErr) {
						console.log(err, codErr);
					}
				});
			},
			delete: function(id) {
				ajax({
					url: '../api/delete-productos.php',
					method: 'delete',
					data: 'id='+ ((id != null) ? id : null),
					success: function(rta) {
						var status = $($$$("page-productos"),'.status');
						if(rta.status == "SUCCESS" || rta.status == "success") {
							CMS.Components.removeStatus(status);

							if (rta.msg && rta.msg.length>0) {
								status.innerHTML = rta.msg;
							}
							status.className += " active bg-success";

							setTimeout(function(){
								CMS.Components.removeStatus(status);
							}, 5500);

							CMS.Components.traerListado({
								serviceUrl: "get-productos.php",
								section: $$$("page-productos"),
								columns: ["id_producto","imagen","nombre","descripcion","precio"]
							});
							
						} else {
							CMS.Components.removeStatus(status);

							if (typeof rta.errors == "object") {
								for (var error in rta.errors) {
									status.innerHTML += rta.errors[error];
								}
							} else {
								status.innerHTML += rta.errors;
							}
							status.className += " active bg-danger";
						}
					},
					error: function(err, codErr) {
						console.log(err, codErr);
					}
				});
			}
		},
		Categoria: {
			init: function($main) {
				var self = this;
				var $formAltaCat = $$$('form-alta-cat');
				var $formEditarCate = $$$('form-editar-cat');
				$formAltaCat.addEventListener('submit', function(e) {
					e.preventDefault();
					self.create(this);
				}, false);

				$formEditarCate.addEventListener('submit', function(e) {
					e.preventDefault();
					ajax({
						url: '../api/update-categorias.php',
						method: 'PUT',
						data: 'nombre=' + this.nombre.value + 
							  '&id=' + this.id.value,
						success: function(rta) {
							var status = $($$$("form-editar-cat"),'.status');
							if(rta.status == "SUCCESS" || rta.status == "success") {
								CMS.Components.removeStatus(status);

								if (rta.msg && rta.msg.length>0) {
									status.innerHTML = rta.msg;
								}
								status.className += " active bg-success";

								setTimeout(function(){
									CMS.Components.removeStatus(status);
									$$$("update-categoria").removeClass("active");
									$$$("update-categoria").previousElementSibling.previousElementSibling.className += " active";
								}, 3500);

								CMS.Components.traerListado({
									serviceUrl: "get-categorias.php",
									section: $$$("page-categorias"),
									columns: ["id_categoria","nombre"]
								});
							} else {
								CMS.Components.removeStatus(status);

								if (typeof rta.errors == "object") {
									for (var error in rta.errors) {
										status.innerHTML += rta.errors[error];
									}
								} else {
									status.innerHTML += rta.errors;
								}
								status.className += " active bg-danger";
							}
						},
						error: function(err, codErr) {
							console.log(err, codErr);
						}
					});
				}, false);
			},
			create: function(form) {
				ajax({
					url: '../api/save-categorias.php',
					method: 'post',
					data: 'nombre='+ form.nombre.value,
					success: function(rta) {
						var status = $($$$("form-alta-cat"),'.status');
						if(rta.status == "SUCCESS" || rta.status == "success") {
							CMS.Components.removeStatus(status);

							if (rta.msg && rta.msg.length>0) {
								status.innerHTML = rta.msg;
							}
							status.className += " active bg-success";

							setTimeout(function(){
								CMS.Components.removeStatus(status);
								$$$("save-categoria").removeClass("active");
								$$$("save-categoria").previousElementSibling.className += " active";
							}, 3500);

							CMS.Components.traerListado({
								serviceUrl: "get-categorias.php",
								section: $$$("page-categorias"),
								columns: ["id_categoria","nombre"]
							});
						} else {
							CMS.Components.removeStatus(status);

							if (typeof rta.errors == "object") {
								for (var error in rta.errors) {
									status.innerHTML += rta.errors[error];
								}
							} else {
								status.innerHTML += rta.errors;
							}
							status.className += " active bg-danger";
						}
					},
					error: function(err, codErr) {
						console.log(err, codErr);
					}
				});
			},
			update: function(id) {
				var $formEditarCate = $$$('form-editar-cat');
				ajax({
					url: '../api/get-categorias.php',
					method: 'get',
					data: 'id='+ ((id != false) ? id : 'false'),
					success: function(rta) {
						if(typeof rta == "object") {
							var $listado_section = $($$$("page-categorias"),".listado-section"),
								$section_update = $$$("update-categoria");
							
							$formEditarCate.id.value = rta.id_categoria;
							$formEditarCate.nombre.value = rta.nombre;
							
							$listado_section.removeClass("active");
							$section_update.className += " active";
						} else {
							console.log("no hay categorias para mostrar");
						}
					},
					error: function(err, codErr) {
						console.log(err, codErr);
					}
				});
			},
			delete: function(id) {
				ajax({
					url: '../api/delete-categorias.php',
					method: 'delete',
					data: 'id='+ ((id != null) ? id : null),
					success: function(rta) {
						var status = $($$$("page-categorias"),'.status');
						if(rta.status == "SUCCESS" || rta.status == "success") {
							CMS.Components.removeStatus(status);

							if (rta.msg && rta.msg.length>0) {
								status.innerHTML = rta.msg;
							}
							status.className += " active bg-success";

							setTimeout(function(){
								CMS.Components.removeStatus(status);
							}, 5500);

							CMS.Components.traerListado({
								serviceUrl: "get-categorias.php",
								section: $$$("page-categorias"),
								columns: ["id_categoria","nombre"]
							});
						} else {
							CMS.Components.removeStatus(status);

							if (typeof rta.errors == "object") {
								for (var error in rta.errors) {
									status.innerHTML += rta.errors[error];
								}
							} else {
								status.innerHTML += rta.errors;
							}
							status.className += " active bg-danger";
						}
					},
					error: function(err, codErr) {
						console.log(err, codErr);
					}
				});
			}
		},
		Usuario: {
			init: function($main) {
				var self = this;
				var $formAltaUsr = $$$('form-alta-usr');
				var $formEditarUsr = $$$('form-editar-usu');
				$formAltaUsr.addEventListener('submit', function(e) {
					e.preventDefault();
					var formData = new FormData(this);
					self.create(formData);
				}, false);

				$formEditarUsr.addEventListener('submit', function(e) {
					e.preventDefault();
					var sexoElejido;
					for (i=0 ; i<$formEditarUsr.sexo.length ; i++) {
						if ($formEditarUsr.sexo[i].checked) {
							sexoElejido = $formEditarUsr.sexo[i].value;
						}
					}
					ajax({
						url: '../api/update-usuarios.php',
						method: 'PUT',
						data: 'id=' + this.id.value + 
							  '&nombre=' + this.nombre.value + 
							  '&apellido=' + this.apellido.value + 
							  '&email=' + this.email.value + 
							  '&usuario=' + this.usuario.value +
							  '&password=' + this.password.value +
							  '&sexo=' + sexoElejido +
							  '&id_nivel=' + this.nivel.value,
						success: function(rta) {
							var status = $($$$("form-editar-usu"),'.status');
							if(rta.status == "SUCCESS" || rta.status == "success") {
								CMS.Components.removeStatus(status);

								if (rta.msg && rta.msg.length>0) {
									status.innerHTML = rta.msg;
								}
								status.className += " active bg-success";

								setTimeout(function(){
									CMS.Components.removeStatus(status);
									$$$("update-usuario").removeClass("active");
									$$$("update-usuario").previousElementSibling.previousElementSibling.className += " active";
								}, 3500);

								CMS.Components.traerListado({
									serviceUrl: "get-usuarios.php",
									section: $$$("page-usuarios"),
									columns: ["id_usuario","imagen","nombre","apellido","email","nivel"]
								});
							} else {
								CMS.Components.removeStatus(status);

								if (typeof rta.errors == "object") {
									for (var error in rta.errors) {
										status.innerHTML += rta.errors[error];
									}
								} else {
									status.innerHTML += rta.errors;
								}
								status.className += " active bg-danger";
							}
						},
						error: function(err, codErr) {
							console.log(err, codErr);
						}
					});
				}, false);
			},
			create: function(formData) {
				ajax({
					url: '../api/save-usuarios.php',
					method: 'post',
					data: formData,
					success: function(rta) {
						var status = $($$$("form-alta-usr"),'.status');
						if(rta.status == "SUCCESS" || rta.status == "success") {
							console.log(rta.ultimoId);
							CMS.Components.removeStatus(status);

							if (rta.msg && rta.msg.length>0) {
								status.innerHTML = rta.msg;
							}
							status.className += " active bg-success";

							setTimeout(function(){
								CMS.Components.removeStatus(status);
								$$$("save-usuario").removeClass("active");
								$$$("save-usuario").previousElementSibling.className += " active";
							}, 3500);

							CMS.Components.traerListado({
								serviceUrl: "get-usuarios.php",
								section: $$$("page-usuarios"),
								columns: ["id_usuario","imagen","nombre","apellido","email","nivel"]
							});
						} else {
							CMS.Components.removeStatus(status);

							if (typeof rta.errors == "object") {
								for (var error in rta.errors) {
									status.innerHTML += rta.errors[error];
								}
							} else {
								status.innerHTML += rta.errors;
							}
							status.className += " active bg-danger";
						}
					},
					error: function(err, codErr) {
						console.log(err, codErr);
					}
				});
			},
			update: function(id) {
				var $formEditarUsr = $$$('form-editar-usu');

				ajax({
					url: '../api/get-usuarios.php',
					method: 'get',
					data: 'id='+ ((id != false) ? id : 'false'),
					success: function(rta) {
						if(typeof rta == "object") {
							var $listado_section = $($$$("page-usuarios"),".listado-section"),
								$section_update = $$$("update-usuario");
							
							$formEditarUsr.id.value = rta.id_usuario;
							$formEditarUsr.nombre.value = rta.nombre;
							$formEditarUsr.apellido.value = rta.apellido;
							$formEditarUsr.email.value = rta.email;
							$formEditarUsr.usuario.value = rta.username;
							
							$listado_section.removeClass("active");
							$section_update.className += " active";
						} else {
							console.log("no hay usuarios para mostrar");
						}
					},
					error: function(err, codErr) {
						console.log(err, codErr);
					}
				});
			},
			delete: function(id) {
				ajax({
					url: '../api/delete-usuarios.php',
					method: 'delete',
					data: 'id='+ ((id != null) ? id : null),
					success: function(rta) {
						var status = $($$$("page-usuarios"),'.status');
						if(rta.status == "SUCCESS" || rta.status == "success") {
							CMS.Components.removeStatus(status);

							if (rta.msg && rta.msg.length>0) {
								status.innerHTML = rta.msg;
							}
							status.className += " active bg-success";

							setTimeout(function(){
								CMS.Components.removeStatus(status);
							}, 5500);

							CMS.Components.traerListado({
								serviceUrl: "get-usuarios.php",
								section: $$$("page-usuarios"),
								columns: ["id_usuario","imagen","nombre","apellido","email","nivel"]
							});
						} else {
							CMS.Components.removeStatus(status);

							if (typeof rta.errors == "object") {
								for (var error in rta.errors) {
									status.innerHTML += rta.errors[error];
								}
							} else {
								status.innerHTML += rta.errors;
							}
							status.className += " active bg-danger";
						}
					},
					error: function(err, codErr) {
						console.log(err, codErr);
					}
				});
			}
		}
	}
};

window.addEventListener('DOMContentLoaded', function() {
	CMS.init();
}, false);
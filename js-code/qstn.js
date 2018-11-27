class Qstn {

	init() {
		this.toolbar();
	}

	build(obj) {

		if(obj.id) {
			this.data = obj;
			this.container = jQuery('.'+obj.id);
			this.preload();
			this.buildTemplate();
			this.buildSections();
			this.buildMessage();
			this.buildEvents();
			this.checkQstn();
			this.initialCheck();
		}
		else {
			console.log('missing form identification');
		}

	}

	preload() {
		if(this.data.preload) {
			eval(this.data.preload);
		}
	}

	buildTemplate() {
		let container, content, id, send, runfunction, api = new API();
		container = this.container;
		id = api.randomString(8, '0123456789abcdefghijklmnopqrstuvwxyz');
		this.data.uid = id;
		if(this.data.ajax.url) { send = this.data.ajax.url } else { send = ''; }
		if(this.data.ajax.runfunction) { runfunction = this.data.ajax.runfunction } else { runfunction = ''; }
		content = 	`<div class="qstn-form qstn-form-`+id+`">
						<div class="qstn-form-content">
							<div class="qstn-form-title"></div>
							<div class="qstn-form-sections"></div>
							<div class="qstn-form-toolbar mp-wrapper">
								<div class="mp-xs-100 mp-sm-50 mp-md-50 mp-lg-50">
									<div class="qstn-form-btn-container mp-wrapper" style="justify-content: flex-start">
										<div class="qstn-form-back-btn mp-wrapper" style="display: none;">back</div>
									</div>
								</div>
								<div class="mp-xs-100 mp-sm-50 mp-md-50 mp-lg-50">
									<div class="qstn-form-btn-container mp-wrapper" style="justify-content: flex-end">
										<div class="qstn-form-btn mp-wrapper mp-25" data-send="`+send+`" data-runfunction="`+runfunction+`" data-id="`+this.data.uid+`" style="`+this.data.submit.button.style+`">continue</div>
									</div>
								</div>
							</div>
						</div>
						<div class="qstn-form-message"></div>
					</div>`;
		container.append(content);
	}

	buildSections() {
		let container, content, sections, item, id, title, uid;
		uid = this.data.uid;
		container = jQuery('.qstn-form-'+uid+' .qstn-form-sections');
		sections = this.data.sections;
		for (var i = 0; i < sections.length; i++) {
			item = sections[i];
			id = (i+1);
			if(item.hasOwnProperty('hasTitle')){ title = `<div class="mp-xs-100 mp-sm-100 mp-md-100 mp-lg-100"><div class="qstn-form-section-title">`+item.title+`</div></div>`;	 }
			else { title = ''; }
			content = 	`<div class="qstn-form-section qstn-form-section-`+id+` mp-wrapper" data-section="`+id+`">
							`+title+`
						</div>`;
			container.append(content);
			this.buildFields(item,id);
		}
	}

	buildMessage() {
		let container, content, uid;
		uid = this.data.uid;
		container = jQuery('.qstn-form-'+uid+' .qstn-form-message');
		content = this.data.submit.message;
		container.append(content);
	}

	buildFields(data,id) {
		let container, content, field, size, required, compare, uid;
		uid = this.data.uid;
		container = jQuery('.qstn-form-'+uid+' .qstn-form-section-'+id);
		for (var i = 0; i < data.fields.length; i++) {
			field = data.fields[i];
			if(!field.size) { size = 'mp-xs-100' } else { size = field.size }
			if(field.params.required) {required = 'qstn-required'} else {required = 'qstn-not-required qstn-form-input-complete'}
			content = 	`<div class="qstn-form-field qstn-form-field-`+id+`-`+i+` `+size+`" id="qstn-form-item-`+field.params.set+`">
							<div class="qstn-form-block">
								<div class="qstn-form-field-title">`+field.title+`</div>
								<div class="qstn-form-input `+required+`" data-type="`+field.params.type+`">
									<div class="qstn-form-input-status"></div>
								</div>
							</div>
						</div>`;
			container.append(content);
			this.buildInput(field,id,i);
		}
	}

	buildInput(data,pid,id) {
		let container, content, uid, unique;
		uid = this.data.uid;
		if(data.params.unique) { unique = data.params.unique; } else { unique = ''; }
		container = jQuery('.qstn-form-'+uid+' .qstn-form-field-'+pid+'-'+id+' .qstn-form-input');
		switch(data.params.type) {
			case 'text':
				content = '<input type="text" class="qstn-form-value '+data.class.item+'" data-min="'+data.params.min+'" data-max="'+data.params.max+'" data-type="'+data.params.type+'" data-set="'+data.params.set+'" data-unique="'+unique+'" placeholder="'+data.placeholder+'" data-required="'+data.params.required+'" autocomplete="new-password">';
			break;
			case 'dynamictext':
				content = '<input type="text" class="qstn-form-value '+data.class.item+'" data-min="'+data.params.min+'" data-max="'+data.params.max+'" data-type="'+data.params.type+'" data-set="'+data.params.set+'" data-unique="'+unique+'" data-target="'+data.operation.target+'" data-action="'+data.operation.action+'" placeholder="'+data.placeholder+'" data-required="'+data.params.required+'" autocomplete="new-password">';
			break;
			case 'phone':
				content = '<input type="tel" class="qstn-form-value '+data.class.item+'" data-min="'+data.params.min+'" data-max="'+data.params.max+'" data-type="'+data.params.type+'" data-set="'+data.params.set+'" data-unique="'+unique+'" placeholder="'+data.placeholder+'" data-required="'+data.params.required+'" autocomplete="new-password">';
			break;
			case 'password':
				content = '<input type="password" class="qstn-form-value '+data.class.item+'" data-min="'+data.params.min+'" data-max="'+data.params.max+'" maxlength="'+data.params.max+'" data-type="'+data.params.type+'" data-set="'+data.params.set+'" data-compare="'+data.params.compare+'" placeholder="'+data.placeholder+'" data-required="'+data.params.required+'" autocomplete="new-password">';
			break;
			case 'textarea':
				content = '<textarea class="qstn-form-value '+data.class.item+'" data-min="'+data.params.min+'" data-max="'+data.params.max+'" data-type="'+data.params.type+'" data-set="'+data.params.set+'" placeholder="'+data.placeholder+'" data-required="'+data.params.required+'"></textarea>';
			break;
			case 'select':
				content = '<select class="qstn-form-value '+data.class.item+'" data-type="'+data.params.type+'" data-set="'+data.params.set+'"></select>';
			break;
			case 'radio':
				content = '<div>radio button</div>';
			break;
			case 'slider':
				content = '<div class="qstn-form-slider-content mp-wrapper">0 - 6 months</div><div class="qstn-form-value qstn-form-slider" id="'+data.params.set+'" data-type="'+data.params.type+'" data-set="'+data.params.set+'" data-value=""></div>';
			break;
			case 'radios':
				content = '<div class="qstn-form-radios '+data.class.item+' mp-wrapper"></div>';
			break;
			case 'notes':
				content = '<div class="qstn-form-notes"></div>';
			break;
			case 'signature':
				content = '<div class="qstn-form-signature" data-id="'+data.params.set+'"></div><div class="qstn-form-signature-image" data-image=""></div>';
			break;
			case 'checkbox':
				content = '<label class="qstn-form-checkbox-label"><input type="checkbox" class="qstn-form-value '+data.class.item+'" data-min="'+data.params.min+'" data-max="'+data.params.max+'" data-type="'+data.params.type+'" data-set="'+data.params.set+'" placeholder="'+data.placeholder+'" data-required="'+data.params.required+'" autocomplete="new-password"><div class="qstn-form-checkbox-icon mp-wrapper"></div></label>';
			break;
			case 'checkboxes':
				content = '<div class="qstn-form-checkboxes '+data.class.item+' mp-wrapper"></div>';
			break;
			case 'datetime':
				content = '<div class="input-group date qstn-form-datetimepicker qstn-form-datetimeonly"><div class="input-group-addon"><input type="text" class="form-control qstn-form-value '+data.class.item+'" data-min="'+data.params.min+'" data-max="'+data.params.max+'" data-type="'+data.params.type+'" data-set="'+data.params.set+'" data-format="dd/MM/yyyy hh:mm:ss" placeholder="'+data.placeholder+'" data-required="'+data.params.required+'"></div></div>';
			break;
			case 'dateonly':
				content = '<div class="input-group date qstn-form-datetimepicker qstn-form-dateonly"><div class="input-group-addon"><input type="text" class="form-control qstn-form-value '+data.class.item+'" data-min="'+data.params.min+'" data-max="'+data.params.max+'" data-type="'+data.params.type+'" data-set="'+data.params.set+'" data-format="dd/MM/yyyy" placeholder="'+data.placeholder+'" data-required="'+data.params.required+'"></div></div>';
			break;
			case 'location':
				content = 	`<div id="mp-page-form-address-search">
								<div class="mp-page-form-item-input" id="`+data.search.form+`">
									<input type="text" class="mp-page-form-item-value qstn-form-value" id="`+data.search.input+`" data-type="`+data.params.type+`" data-set="`+data.params.set+`" placeholder="25521 Spectrum Irvine, CA 92618">
									<input type="hidden" class="mp-page-form-address-lat" name="lat" value="">
			                    	<input type="hidden" class="mp-page-form-address-lon" name="lng" value="">
									<div class="mp-page-form-address-results"></div>
								</div>
							</div>`;
			break;
		}
		container.append(content);

		switch(data.params.type) {
			case 'notes':
				let notes;
				notes = jQuery('.qstn-form-field-'+pid+'-'+id+' .qstn-form-notes');
				notes.append('<div>'+data.params.data+'</div>');
			break;
			case 'select':
				let select;
				select = jQuery('.qstn-form-field-'+pid+'-'+id+' select');
				select.append('<option disabled selected value>select an option</option>');
				data.params.options.forEach(function(element){select.append('<option data-type="'+data.params.type+'" value="'+element.value+'">'+element.title+'</option>');});
			break;
			case 'checkboxes':
				let checkboxes;
				checkboxes = jQuery('.qstn-form-field-'+pid+'-'+id+' .'+data.class.item);
				data.params.checkbox.forEach(function(element){checkboxes.append(
					`<div class="mp-xs-100 mp-sm-50 mp-md-33 mp-lg-33">
						<div class="qstn-form-checkbox-holder mp-wrapper">
							<div class="qstn-form-checkbox-item">
								<label class="qstn-form-checkbox-label">
									<input type="checkbox" class="qstn-form-value `+data.class.item+`" value="`+element.value+`" data-type="`+data.params.type+`" data-set="`+data.params.set+`" data-required="`+data.params.required+`">
									<div class="qstn-form-checkbox-icon mp-wrapper"></div>
								</label>
							</div>
							<div class="qstn-form-checkbox-title mp-wrapper">`+element.title+`</div>
						</div>
					</div>`
				)});
			break;
			case 'radios':
				let radios;
				radios = jQuery('.qstn-form-field-'+pid+'-'+id+' .'+data.class.item);
				data.params.radio.forEach(function(element){radios.append(
					`<div class="`+data.params.sizes+`">
						<div class="qstn-form-radio-holder mp-wrapper">
							<div class="qstn-form-radio-item mp-wrapper">
								<label class="qstn-form-radio-label">
									<input type="radio" id="`+data.class.item+`" name="`+data.params.set+`" class="qstn-form-value `+data.class.item+`" value="`+element.value+`" data-type="`+data.params.type+`" data-set="`+data.params.set+`" data-required="`+data.params.required+`">
									<div class="qstn-form-radio-icon mp-wrapper"></div>
								</label>
							</div>
							<div class="qstn-form-radio-title mp-wrapper">`+element.title+`</div>
						</div>
					</div>`
				)});
			break;
			case 'slider':
				let slider = document.getElementById(data.params.set);
				let pipFormats = data.params.pips;
				noUiSlider.create(slider, {
				    start: [2],
				    step: 1,
				    range: {
				        'min': [1],
				        'max': [10]
				    },
				    pips: {
				        mode: 'range',
				        density: 11,
				        stepped: true
				  //       format: {
						// 	to: function(a){
						// 		return pipFormats[a];
						// 	}
						// }
				    }
				});
				jQuery('.qstn-form-slider').siblings('.qstn-form-input-status').css({'top':'-18px'});
			break;
			case 'location':
				var searcher = new Searcher();
				searcher.search({'form':data.search.form,'input':data.search.input});
			break;
			case 'signature':
				let sign = new Sign(); 
				sign.init({'type':data.params.type,'id':data.params.set});
			break;
		}

	}

	buildEvents() {
		jQuery('.qstn-form-section:first-child').addClass('active');
		jQuery('.qstn-form-phone-mask').mask('(000) 000-0000');
		jQuery('.qstn-form-ssn-mask').mask('000-00-0000');
		jQuery('.qstn-form-date-mask').mask('00/00/0000');
		jQuery('.qstn-form-license-mask').mask('A-0000000');
		jQuery('.qstn-form-datetimeonly input').attr('placeholder',moment().format('MMMM Do YYYY, h:mm A'));
		jQuery('.qstn-form-datetimeonly').datetimepicker({
            format: 'MMMM Do YYYY, h:mm A',
		});
		jQuery('.qstn-form-dateonly input').attr('placeholder',moment().format('MMMM Do YYYY'));
		jQuery('.qstn-form-dateonly').datetimepicker({
            format: 'MMMM Do YYYY',
		});
	}

	initialCheck() {
		let uid, data, qstn = new Qstn();
		uid = this.data.uid;
		data = qstn.checkSection(uid);
		this.validateSubmit(data);
		this.checkBtns();
	}

	checkQstn() {
		let uid, qstn = new Qstn();
		uid = this.data.uid;
		jQuery('.qstn-form-'+uid+' .qstn-form-value').each(function() {
			let item, data, value, length, type, target, required, slider, typing;
			item = jQuery(this);
			type = item.data('type');
			required = item.data('required');
			typing = null;
			if(item.hasClass('qstn-form-slider')) {
				slider = document.getElementById(item.attr('id'));
				slider.noUiSlider.on('update', function () {
					item.data('value', parseInt(slider.noUiSlider.get()));
					qstn.checkField(item);
					data = qstn.checkSection(uid);
					qstn.validateSubmit(data);
					console.log(data);
				});
			}
			item.on('keyup', function() {
				value = item.val();
				length = value.length;
				if(type == 'dynamictext') {
					target = jQuery('#'+item.data('target'));
					target.html(value);
				}
				if(required == false) {
					item.parent().removeClass('qstn-form-input-error').addClass('qstn-form-input-complete');
				}
				else {
					if(length == 0 || !value) {
						item.parent().removeClass('qstn-form-input-complete qstn-form-input-error');
					}
					else {
						// Wait until user types something
						clearInterval(typing);

						typing = setInterval(function() {
							// User has stopped typing
							clearInterval(typing);
							qstn.checkField(item);
							data = qstn.checkSection(uid);
							qstn.validateSubmit(data);
						}, 500);
					}
				}
			});
			item.on('change', function() {
				qstn.checkField(item);
				data = qstn.checkSection(uid);
				qstn.validateSubmit(data);
				if(required == false) {
					// item.parent().removeClass('qstn-form-input-error').addClass('qstn-form-input-complete');
				}
				else {

				}
			});
			item.closest('.qstn-form-datetimeonly, .qstn-form-dateonly').on('dp.change', function(e){
				qstn.checkField(item);
				data = qstn.checkSection(uid);
				qstn.validateSubmit(data);
			});
			item.bind('change', function(e){
				let signData = item.jSignature('getData', e.target.value);
				if(signData) {
					if( item.jSignature('getData', 'native').length == 0) {
						// no data
						let id;
						id = item.attr('id');
						jQuery('#qstn-form-item-'+id+' .qstn-form-input').addClass('qstn-form-input-error').removeClass('qstn-form-input-complete');
						jQuery('#qstn-form-item-'+id+' #'+id).data('value','');
						jQuery('#qstn-form-item-'+id+' .qstn-form-signature-image').hide();
					}
					else {
						// has data
						let id, image = new Image();
						image.src = signData;
						id = item.attr('id');
						jQuery('#qstn-form-item-'+id+' .qstn-form-input').removeClass('qstn-form-input-error').addClass('qstn-form-input-complete');
						jQuery('#qstn-form-item-'+id+' #'+id).data('value',signData);
						jQuery('#qstn-form-item-'+id+' .qstn-form-signature-image').show().html('<img src="'+image.src+'" width="100%">');
					}
					qstn.checkField(item);
					data = qstn.checkSection(uid);
					qstn.validateSubmit(data);
				}
			});
		});
	}

	checkField(item) {
		let type;
		type = item.data('type');
		switch(type) {
			case 'text':
			case 'dynamictext':
				this.validateText(item);
			break;
			case 'location':
				this.validateAddress(item);
			break;
			case 'phone':
				this.validatePhone(item);
			break;
			case 'password':
				this.validatePassword(item);
			break;
			case 'textarea':
				this.validateTextarea(item);
			break;
			case 'select':
				this.validateSelect(item);
			break;
			case 'datetimeonly':
				this.validateDatetime(item);
			break;
			case 'dateonly':
				this.validateDatetime(item);
			break;
			case 'checkbox':
				this.validateCheckbox(item);
			break;
			case 'checkboxes':
				this.validateCheckboxes(item);
			break;
			case 'radios':
				this.validateRadios(item);
			break;
			case 'slider':
				this.validateSlider(item);
			break;
		}
	}

	checkSection(uid) {
		let id = 1, sections = {}, section, input, total, complete, required, notrequired, incomplete;
		jQuery('.qstn-form-'+uid+' .qstn-form-section').each(function() {
			let item, fields = [];
			item = jQuery(this);
			if(item.hasClass('active')) { section = item.data('section'); }
			input = item.find('.qstn-form-input');
			input.find('.qstn-form-value').each(function() {
				let data;
				data = jQuery(this);
				switch(data.data('type')) {
					case 'checkboxes':
						if(data.is(':checked')) {
							fields.push({'value':data.val(),'type':data.data('type'),'group':data.data('set')});
						}
					break;
					case 'signature':
						fields.push({'value':data.data('value'),'type':data.data('type'),'group':data.data('set')});
					break;
					case 'slider':
						fields.push({'value':data.data('value'),'type':data.data('type'),'group':data.data('set')});
					break;
					default:
						fields.push({'value':data.val(),'type':data.data('type'),'group':data.data('set')});
					break;
				}
			});
			total = input.length;
			complete = jQuery('.qstn-form-'+uid+' .qstn-form-section-'+id+' .qstn-form-input-complete').length;
			required = jQuery('.qstn-form-'+uid+' .qstn-form-section-'+id+' .qstn-required').length;
			notrequired = jQuery('.qstn-form-'+uid+' .qstn-form-section-'+id+' .qstn-not-required').length;
			incomplete = total - complete;
			sections[id] = {'complete':complete,'incomplete':incomplete,'required':required,'notrequired':notrequired,'total':total,'fields':fields};
			sections.current = section;
			sections.count = id;
			sections.uid = uid;
			id++;
		});
		return sections;		
	}

	validateText(item) {
		let value, length, min, max, set;
		value = item.val();
		length = value.length;
		min = item.data('min');
		max = item.data('max');
		set = item.data('set');

		if(length > max || length < min) {
			if(item.data('required') == false) {

			}
			else {
				item.parent().addClass('qstn-form-input-error').removeClass('qstn-form-input-complete');
			}
		}
		else {
			switch(set) {
				case 'email':
					this.validateEmail(item);
				break;
				case 'website':
					this.validateWebsite(item);
				break;
				default:
					this.validateDefault(item);
				break;
			}
		}

	}

	validatePhone(item) {
		let value, length, min, max;
		value = item.val();
		length = value.length;
		min = item.data('min');
		max = item.data('max');
    	if(length == max && length == min) {
    		item.parent().addClass('qstn-form-input-complete').removeClass('qstn-form-input-error');
    	}
		else {
			item.parent().addClass('qstn-form-input-error').removeClass('qstn-form-input-complete');
		}
	}

	validatePassword(item) {
		let value, length, min, max, validator, pass, compare, passCompare;
		value = item.val();
		length = value.length;
		min = item.data('min');
		max = item.data('max');
		compare = item.data('compare');
		passCompare = jQuery('#qstn-form-item-'+compare+' input').val();
		validator = new RegExp("^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{"+min+","+max+"})");
		pass = validator.test(String(value));
		if(pass) {
			if(compare.length > 0) {
				if(value == passCompare) {
					item.parent().addClass('qstn-form-input-complete').removeClass('qstn-form-input-error');
					jQuery('#qstn-form-item-'+compare+' input').parent().addClass('qstn-form-input-complete').removeClass('qstn-form-input-error');
				}
				else {
					item.parent().addClass('qstn-form-input-error').removeClass('qstn-form-input-complete');
					jQuery('#qstn-form-item-'+compare+' input').parent().addClass('qstn-form-input-error').removeClass('qstn-form-input-complete');
				}
			}
			else {
				item.parent().addClass('qstn-form-input-complete').removeClass('qstn-form-input-error');
			}
		}
		else {
			item.parent().addClass('qstn-form-input-error').removeClass('qstn-form-input-complete');
		}
	}

	validateTextarea(item) {
		let value, length, min, max;
		value = item.val();
		length = value.length;
		min = item.data('min');
		max = item.data('max');
		if(length > max || length < min) {
			item.parent().addClass('qstn-form-input-error').removeClass('qstn-form-input-complete');
		}
		else {
			this.validateDefault(item);
		}
	}

	validateSelect(item) {
		let value, length;
		value = item.val();
		if(value) {
			this.validateDefault(item);
		}
		else {
			item.parent().addClass('qstn-form-input-error').removeClass('qstn-form-input-complete');
		}
	}

	validateEmail(item) {
		let value, unique, un, validator;
		value = item.val();
		if(item.data('unique')) { unique = eval('window.'+item.data('unique')); } else { unique = false; }
		validator = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    	if(validator.test(String(value).toLowerCase())) {
    		item.parent().addClass('qstn-form-input-complete').removeClass('qstn-form-input-error');
    		if(unique) {
    			unique.forEach(function(element) {
					if(element.email == value) {
						item.parent().addClass('qstn-form-input-error').removeClass('qstn-form-input-complete');
						console.log('email already exists');
					}
		    	});
    		}
    	}
		else {
			item.parent().addClass('qstn-form-input-error').removeClass('qstn-form-input-complete');
		}
	}

	validateWebsite(item) {
		let value, validator;
		value = item.val();
		validator = /[-a-zA-Z0-9@:%_\+.~#?&//=]{2,256}\.[a-z]{2,4}\b(\/[-a-zA-Z0-9@:%_\+.~#?&//=]*)?/gi;
		if(validator.test(String(value).toLowerCase())) {
    		item.parent().addClass('qstn-form-input-complete').removeClass('qstn-form-input-error');
    	}
		else {
			item.parent().addClass('qstn-form-input-error').removeClass('qstn-form-input-complete');
		}
	}

	validateDatetime(item) {
		let value, length, min, max;
		value = item.val();
		length = value.length;
		min = item.data('min');
		max = item.data('max');
		if(length > max || value.length < min) {
			item.closest('.qstn-form-input').addClass('qstn-form-input-error').removeClass('qstn-form-input-complete');
		}
		else {
			item.closest('.qstn-form-input').addClass('qstn-form-input-complete').removeClass('qstn-form-input-error');
		}
	}

	validateCheckbox(item) {
		let value;
		if(item.is(':checked')) {
        	item.parent().parent().addClass('qstn-form-input-complete').removeClass('qstn-form-input-error');
        	item.siblings('.qstn-form-checkbox-icon').html('<span class="fa fa-check"></span>');
    	}
		else {
			item.parent().parent().addClass('qstn-form-input-error').removeClass('qstn-form-input-complete');
			item.siblings('.qstn-form-checkbox-icon').html('');
		}
	}

	validateCheckboxes(item) {
		let value;
		if(item.data('required') == false) {
			if(item.is(':checked')) {
	        	item.siblings('.qstn-form-checkbox-icon').html('<span class="fa fa-check"></span>');
	    	}
			else {
				item.siblings('.qstn-form-checkbox-icon').html('');
			}
		}
		else {
			if(item.is(':checked')) {
	        	item.closest('.qstn-form-input').addClass('qstn-form-input-complete').removeClass('qstn-form-input-error');
	        	item.siblings('.qstn-form-checkbox-icon').html('<span class="fa fa-check"></span>');
	    	}
			else {
				item.closest('.qstn-form-input').addClass('qstn-form-input-error').removeClass('qstn-form-input-complete');
				item.siblings('.qstn-form-checkbox-icon').html('');
			}
		}
	}

	validateSlider(item) {
	    item.closest('.qstn-form-input').addClass('qstn-form-input-complete').removeClass('qstn-form-input-error');
    	jQuery('#qstn-form-item-'+item.attr('name')+' .qstn-form-radio-icon').html('');
    	item.siblings('.qstn-form-radio-icon').html('<span class="fa fa-check"></span>');
	}

	validateRadios(item) {
		let value;
		if(item.data('required') == false) {
			if(item.is(':checked')) {
	        	// item.siblings('.qstn-form-radio-icon').html('<span class="fa fa-check"></span>');
	    	}
			else {
				// item.siblings('.qstn-form-radio-icon').html('');
			}
		}
		else {
			if(item.is(':checked')) {
	        	item.closest('.qstn-form-input').addClass('qstn-form-input-complete').removeClass('qstn-form-input-error');
	        	jQuery('#qstn-form-item-'+item.attr('name')+' .qstn-form-radio-icon').html('');
	        	item.siblings('.qstn-form-radio-icon').html('<span class="fa fa-check"></span>');
	    	}
			else {
				item.closest('.qstn-form-input').addClass('qstn-form-input-error').removeClass('qstn-form-input-complete');
				item.siblings('.qstn-form-radio-icon').html('');
			}
		}
	}

	validateAddress(item) {
		let value, length;
		value = item.val();
		length = value.length;
		if(value.length < 1) {
			item.closest('.qstn-form-input').addClass('qstn-form-input-error').removeClass('qstn-form-input-complete');
		}
	}

	validateDefault(item) {
		if(item.data('required') == false) {

		}
		else {
			item.parent().addClass('qstn-form-input-complete').removeClass('qstn-form-input-error');	
		}
	}

	validateSubmit(obj) {
		let current, complete, incomplete, total, count, uid, qstn = new Qstn();

		current = obj.current;
		complete = obj[current].complete;
		total = obj[current].total;
		count = obj.count;
		uid = obj.uid;

		if(complete == total) {
			jQuery('.qstn-form-'+uid+' .qstn-form-section:nth-child('+current+')').addClass('qstn-section-complete').removeClass('qstn-section-incomplete');
			jQuery('.qstn-form-'+uid+' .qstn-form-btn').addClass('ready');
		}
		else {
			jQuery('.qstn-form-'+uid+' .qstn-form-section:nth-child('+current+')').removeClass('qstn-section-complete').addClass('qstn-section-incomplete');
			jQuery('.qstn-form-'+uid+' .qstn-form-btn').removeClass('ready');	
		}

	}

	validateNext(obj) {
		let current, complete, incomplete, total, count, uid, qstn = new Qstn();

		count = obj.count;
		uid = obj.uid;
		current = obj.current + 1;
		if(current <= count) {
			complete = obj[current].complete;
			total = obj[current].total;
			if(complete == total) {
				jQuery('.qstn-form-'+uid+' .qstn-form-section:nth-child('+current+')').addClass('qstn-section-complete').removeClass('qstn-section-incomplete');
				jQuery('.qstn-form-'+uid+' .qstn-form-btn').addClass('ready');
			}
			else {
				jQuery('.qstn-form-'+uid+' .qstn-form-section:nth-child('+current+')').removeClass('qstn-section-complete').addClass('qstn-section-incomplete');
				jQuery('.qstn-form-'+uid+' .qstn-form-btn').removeClass('ready');	
			}
		}
	}

	toolbar() {
		this.checkBtns();
		this.backQstn();
		this.submitQstn();
	}

	checkBtns() {
		let item, sections, section, id;
		sections = jQuery('.qstn-form-section').length;
		section = jQuery('.qstn-form-section.active');
		id = section.data('section');
		switch(id) {
			case 1:
				jQuery('.qstn-form-back-btn').hide();
			break;
			default:
				jQuery('.qstn-form-back-btn').show();
			break;
		}
		if(section.hasClass('qstn-section-complete')) {
			jQuery('.qstn-form-btn').addClass('ready');
		}
		else {
			jQuery('.qstn-form-btn').removeClass('ready');
		}

		setTimeout(function() {
			jQuery('.qstn-form-signature').each(function(i,el) {
				let id, width;
				id = jQuery(el).data('id');
				width = jQuery('#qstn-form-item-'+id+' .qstn-form-signature').width();
				jQuery('#qstn-form-item-'+id+' .jSignature').css({'height':'120px','width':width+'px'}).attr('height','80').attr('width',width);
			});
		}, 50);
	}

	backQstn() {
		let item, sections, section, id, data, qstn = new Qstn();
		jQuery('.qstn-form-back-btn').on('click', () => {

			jQuery('html, body').animate({
		        scrollTop: jQuery('html, body').offset().top - 250
		    }, 700);

			item = jQuery(this);
			sections = jQuery('.qstn-form-section').length;
			section = jQuery('.qstn-form-section.active');
			id = section.data('section');
			switch(id) {
				case 1:
					item.hide();
				break;
				default:
					item.show();
					jQuery('.qstn-form-section').removeClass('active');
					jQuery('.qstn-form-section:nth-child('+(id-1)+')').addClass('active');
					jQuery('.qstn-form-btn').addClass('ready');
				break;
			}
			qstn.checkBtns();
		});
	}

	submitQstn() {
		let item, sections, current, complete, incomplete, total, count, uid, send, runfunction, info, qstn = new Qstn(), api = new API(), messenger = new Messenger();
		jQuery('.qstn-form-btn').on('click', function() {

			jQuery('html, body').animate({
		        scrollTop: jQuery('body').offset().top
		    }, 700);

			// setTimeout(() => {
			// 	width = signbox.width();
			// 	jQuery('.jSignature').css({'height':'120px','width':width+'px'}).attr('height','120').attr('width',width);
			// }, 150);

			item = jQuery(this);
			uid = item.data('id');
			send = item.data('send');
			runfunction = item.data('runfunction');
			sections = qstn.checkSection(uid);
			current = sections.current;
			complete = sections[current].complete;
			total = sections[current].total;
			count = sections.count;
			
			if(complete == total) {
				if(current == count) {
					let tag, id, name, data = [];
					tag = api.randomString(8, 'abcdefghijklmnopqrstuvwxyz1234567890');
					id = api.randomString(8, 'abcdefghijklmnopqrstuvwxyz');
					name = api.randomString(8, 'abcdefghijklmnopqrstuvwxyz');
					
					// grab all data from input fields and store in new array -> data
					for (var i = 1; i <= count; i++) {
						for (var ii = 0; ii < sections[i].fields.length; ii++) {
							data.push(sections[i].fields[ii]);
						}
					}

					data.push({'value':tag,'type':'tag'});
					jQuery('.qstn-form-'+uid+' .qstn-form-content').slideUp(250);
					jQuery('.qstn-form-'+uid+' .qstn-form-message').slideDown(250);

					// ajax
					if(send.length > 0) {
						jQuery.ajax
						({
							type: 'POST',
							url: '/'+send,
							data: { info: data },
							cache: false,
							beforeSend: function() {
							   console.log('sent');
							},
							success: function(html) {
							   console.log(html);
							},
							complete: function() {
							   console.log('complete');
							}
						});
					}
					else if(runfunction.length > 0) {
						eval(runfunction);
					}
					else {

					}

					// run messenger
					messenger.run({
						'id':id,
						'name':name,
						'title':'Success!',
						'message':'Form was successfully sent',
						'duration':4500,
						'theme':'light',
						'icon':'check',
						'color':'#37bfb1',
						'location':'bottom-right',
						'button':{
							'title':'',
							'link':''
						}
					});

				}
				else {
					jQuery('.qstn-form-'+uid+' .qstn-form-section').removeClass('active');
					jQuery('.qstn-form-'+uid+' .qstn-form-section:nth-child('+current+')').addClass('qstn-section-complete').removeClass('qstn-section-incomplete');
					jQuery('.qstn-form-'+uid+' .qstn-form-section:nth-child('+(current+1)+')').addClass('active');
					jQuery('.qstn-form-'+uid+' .qstn-form-btn').removeClass('ready');
				}
			}
			else {
				let id, name;
				id = api.randomString(8, 'abcdefghijklmnopqrstuvwxyz');
				name = api.randomString(8, 'abcdefghijklmnopqrstuvwxyz');
				messenger.run({
					'id':id,
					'name':name,
					'title':'ERROR!',
					'message':'please finish the current section',
					'duration':4500,
					'theme':'light',
					'icon':'exclamation',
					'color':'#f92b30',
					'location':'bottom-right',
					'button':{
						'title':'',
						'link':''
					}
				});
			}

			qstn.validateNext(sections);
			qstn.checkBtns();

		});
	}

}
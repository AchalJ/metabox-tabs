;(function($) {

    /**
	 * The main metabox interface class.
	 *
	 * @since 1.0
	 * @class MBT
	 */
    MBT = {
        /**
		 * Initialize the metabox interface.
		 *
		 * @since 1.0
		 * @access private
		 * @method _init
		 */
        _init: function()
        {
            MBT._initTabs();
            MBT._bindEvents();
            MBT._initFields();
        },

        /**
		 * Initialize metabox tabs.
		 *
		 * @since 1.0
		 * @access private
		 * @method _initTabs
		 */
        _initTabs: function()
        {
            // Tabs.
            var navEl       = $('.mbt-metabox-tabs'),
                container   = $(navEl.data('container')),
                tabHash     = window.location.hash,
                currentTab  = window.location.hash.replace( '!', '' );

            // If the URL contains a hash beginning with cf-tab, mark that tab as open
            // and display that tab's panel.
            if ( tabHash && tabHash.indexOf('mbt-metabox-tab-') >= 0 ) {

                // Remove the active class from everything in this tab navigation and section.
                container.find('.mbt-metabox-tab-content').removeClass('active');
                navEl.find('.mbt-metabox-tab').removeClass('active');

                // Add the active class to the chosen tab and section.
                $(currentTab).addClass('active');
                navEl.find('a[href="'+currentTab+'"]').parent().addClass('active');

                // Update the form action to contain the selected tab as a hash in the URL
                // This means when the user saves their Giveaway, they'll see the last selected
                // tab 'open' on reload.
                var postAction = $('#post').attr('action');
                if ( postAction ) {
                    // Remove any existing hash from the post action.
                    postAction = postAction.split('#')[0];

                    // Append the selected tab as a hash to the post action.
                    $( '#post' ).attr( 'action', postAction + window.location.hash );
                }
            }

            navEl.find('a').on('click', function(e) {
                // Prevent the default action.
                e.preventDefault();

                // Remove the active class from everything in this tab navigation and section.
                container.find('.mbt-metabox-tab-content').removeClass('active');
                navEl.find('.mbt-metabox-tab').removeClass('active');

                // Get the nav tab ID.
                var tabId = $(this).attr('href');

                // Add the active class to the chosen tab and section.
                $(tabId).addClass('active');
                $(this).parent().addClass('active');

                // Update the window URL to contain the selected tab as a hash in the URL.
                window.location.hash = tabId.split('#').join('#!');

                // Update the form action to contain the selected tab as a hash in the URL
                // This means when the user saves their Giveaway, they'll see the last selected
                // tab 'open' on reload.
                var postAction = $('#post').attr('action');
                if ( postAction ) {
                    // Remove any existing hash from the post action.
                    postAction = postAction.split('#')[0];

                    // Append the selected tab as a hash to the post action.
                    $( '#post' ).attr( 'action', postAction + window.location.hash );
                }
            });
        },

        /**
		 * Initialize metabox fields.
		 *
		 * @since 1.0
		 * @access private
		 * @method _initFields
		 */
        _initFields: function()
        {
            $('.mbt-metabox-tabs-wrapper .mbt-input-field').trigger('change');

            MBT._initColorField();
            MBT._initPhotoField();
            MBT._initGroupField();
        },

        /**
		 * Initialize color field.
		 *
		 * @since 1.0
		 * @access private
		 * @method _initColorField
		 */
        _initColorField: function()
        {
            if ( 'undefined' !== typeof $.fn.wpColorPicker ) {
                // Add Color Picker to all inputs that have 'mbt-color-picker' class.
                $( '.mbt-color-picker' ).each(function() {
                    var color = $(this).val();
                    $(this).wpColorPicker().parents('.wp-picker-container').find('.wp-color-result').css('background-color', '#' + color);
                });
            }
        },

        /**
		 * Initialize photo field.
		 *
		 * @since 1.0
		 * @access private
		 * @method _initPhotoField
		 */
        _initPhotoField: function()
        {
            if ( $('.mbt-field[data-type="photo"]').length === 0 ) {
                return;
            }

            $('.mbt-field[data-type="photo"]').each(function () {
                var wrapper     = $(this),
                    urlField    = $(this).find('input.mbt-img-url'),
                    idField     = $(this).find('input.mbt-img-id');
                if ( '' !==  urlField.val().trim() && '' === idField.val().trim() ) {
                    wrapper.find('.mbt-img-container').addClass('mbt-has-img').html('<img src="'+urlField.val()+'" style="max-width:100%;" />');
                    wrapper.find('.mbt-img-action .mbt-upload-img').addClass('hidden');
                    wrapper.find('.mbt-img-action .mbt-delete-img').removeClass('hidden');
                }
            });
        },

        /**
		 * Initialize group field.
		 *
		 * @since 1.0
		 * @access private
		 * @method _initGroupField
		 */
        _initGroupField: function()
        {
            if ( $('.mbt-field[data-type="group"]').length === 0 ) {
                return;
            }

            var field = $('.mbt-field[data-type="group"]');

            field.each(function() {

                var $this       = $(this),
                    groups      = $this.find('.mbt-fields-group'),
                    firstGroup  = $this.find('.mbt-fields-group:first'),
                    lastGroup   = $this.find('.mbt-fields-group:last');

                groups.each(function() {
                    var groupContent = $(this).find('.mbt-fields-group-title:not(.open)').next();
                    if ( groupContent.is(':visible') ) {
                        groupContent.slideToggle(0);
                    }
                });

                // Reset group ids.
                MBT._resetGroupFieldIds(groups);

                firstGroup.find('.mbt-fields-group-order .mbt-fields-group-up').addClass('disabled');
                lastGroup.find('.mbt-fields-group-order .mbt-fields-group-down').addClass('disabled');

                if ( groups.length === 1 ) {
                    groups.find('a.mbt-fields-group-remove').addClass('disabled');
                }

                $this.find('.mbt-fields-group-action').on('click', '.mbt-fields-group-add', function(e) {

                    e.preventDefault();

                    var fieldId     = $this.attr('id'),
                        wrapper     = $this.find('.mbt-fields-group-wrapper'),
                        groups      = $this.find('.mbt-fields-group'),
                        firstGroup  = $this.find('.mbt-fields-group:first'),
                        lastGroup   = $this.find('.mbt-fields-group:last'),
                        clone       = $($this.find('.mbt-fields-group-template').html()),
                        groupId     = parseInt(lastGroup.data('group-id')),
                        nextGroupId = groupId + 1,
                        title       = clone.data('group-title');

                    groups.each(function() {
                        $(this).find('.mbt-fields-group-title').next().slideUp(0);
                        groups.find('a.mbt-fields-group-remove').removeClass('disabled');
                    });

                    groups.find('.mbt-fields-group-order a').removeClass('disabled');

                    // Reset all data of clone object.
                    clone.attr('data-group-id', nextGroupId);
                    clone.find('.mbt-fields-group-title .mbt-group-field-title-text').html(title + ' ' + nextGroupId);

                    clone.find('tr.mbt-field[id*='+fieldId+']').each(function() {
                        var fieldName       = $(this).attr('id').split('[1]')[0].split('mbt-field-')[1];
                        var fieldNameSuffix = $(this).attr('id').split('[1]')[1];
                        var nextFieldId     = fieldId + '[' + nextGroupId + ']' + fieldNameSuffix;
                        var label           = $(this).find('th label');

                        $(this).find('[name*="'+fieldName+'[1]"]').each(function() {
                            var inputName       = $(this).attr('name').split('[1]');
                            var inputNamePrefix = inputName[0];
                            var inputNameSuffix = inputName[1];
                            var newInputName    = inputNamePrefix + '[' + nextGroupId + ']' + inputNameSuffix;
                            $(this).attr('id', newInputName).attr('name', newInputName);
                            label.attr('for', newInputName);
                        });

                        $(this).attr('id', nextFieldId);
                    });

                    clone.find('.mbt-fields-group-remove').attr('data-remove-group', nextGroupId);

                    clone.find('.mbt-fields-group-clone').attr('data-clone-group', nextGroupId);

                    clone.find('.mbt-fields-group-order .mbt-fields-group-down').addClass('disabled');

                    clone.addClass('mbt-fields-group-new');

                    clone.insertBefore($(this).parent());

                    // Reset group ids.
                    MBT._resetGroupFieldIds($this.find('.mbt-fields-group'));

                    $this.find('.mbt-fields-group-footer a.mbt-fields-group-order').removeClass('disabled');
                    $this.find('.mbt-fields-group-footer a.mbt-fields-group-remove').removeClass('disabled');

                    firstGroup.find('.mbt-fields-group-order .mbt-fields-group-up').addClass('disabled');
                });
            });

        },

        /**
		 * Bind events.
		 *
		 * @since 1.0
		 * @access private
		 * @method _bindEvents
		 */
        _bindEvents: function()
        {
            $('body').delegate( '.mbt-metabox-tabs-wrapper .mbt-input-field', 'change', function() {
                MBT._fieldChange(this);
            } );
            $('body').delegate( '.mbt-metabox-tabs-wrapper .mbt-field[data-type="photo"] a.mbt-upload-img', 'click', function(e) {
                e.preventDefault();
                MBT._imageUploadTrigger(this);
            } );
            $('body').delegate( '.mbt-metabox-tabs-wrapper .mbt-field[data-type="photo"] a.mbt-delete-img', 'click', function(e) {
                e.preventDefault();
                MBT._imageRemoveTrigger(this);
            } );
            $('body').delegate( '.mbt-metabox-tabs-wrapper .mbt-fields-group .mbt-fields-group-title', 'click', function() {
                MBT._groupFieldToggle(this);
            } );
            $('body').delegate( '.mbt-metabox-tabs-wrapper .mbt-fields-group a.mbt-fields-group-remove', 'click', function() {
                MBT._groupFieldRemove(this);
            } );
            $('body').delegate( '.mbt-metabox-tabs-wrapper .mbt-fields-group a.mbt-fields-group-clone', 'click', function() {
                MBT._groupFieldClone(this);
            } );
            $('body').delegate( '.mbt-metabox-tabs-wrapper .mbt-fields-group .mbt-fields-group-up', 'click', function() {
                MBT._groupFieldMoveUp(this);
            } );
            $('body').delegate( '.mbt-metabox-tabs-wrapper .mbt-fields-group .mbt-fields-group-down', 'click', function() {
                MBT._groupFieldMoveDown(this);
            } );
        },

        /**
		 * Callback for when a settings form field has been changed.
		 * If toggle data is present, other fields will be toggled
		 * when this field changes.
		 *
		 * @since 1.0
		 * @access private
		 * @method _fieldChange
		 */
        _fieldChange: function(input)
        {
            var field   = $(input),
                id      = field.attr('id'),
                toggle  = field.data('toggle'),
                val     = field.val(),
                i       = 0;

            if ( 'checkbox' === field.attr('type') && ! field.is(':checked') ) {
                val = '0';
            }

            if ( field.hasClass('mbt-post-type-field') ) {
                if ( $('select[data-post-type-field="'+id+'"]').length > 0 ) {
                    MBT._updatePostTypeField( val, $('select[data-post-type-field="'+id+'"]') );
                }
            }

            // TOGGLE sections or fields.
    		if ( typeof toggle !== 'undefined' ) {

                if ( typeof toggle !== 'object' ) {
    			    toggle = JSON.parse(toggle);
                }

    			for(i in toggle) {
    				MBT._fieldToggle(toggle[i].fields, 'hide', '#mbt-field-');
    				MBT._fieldToggle(toggle[i].sections, 'hide', '#mbt-metabox-section-');
    				MBT._fieldToggle(toggle[i].tabs, 'hide', 'li.mbt-metabox-tab[data-tab=', ']');
    			}

    			if(typeof toggle[val] !== 'undefined') {
    				MBT._fieldToggle(toggle[val].fields, 'show', '#mbt-field-');
    				MBT._fieldToggle(toggle[val].sections, 'show', '#mbt-metabox-section-');
                    MBT._fieldToggle(toggle[i].tabs, 'show', 'li.mbt-metabox-tab[data-tab=', ']');
    			}
    		}
        },

        /**
		 * @since 1.0
		 * @access private
		 * @method _fieldToggle
		 * @param {Array} inputArray
		 * @param {Function} func
		 * @param {String} prefix
		 * @param {String} suffix
		 */
        _fieldToggle: function(inputArray, func, prefix, suffix)
        {
            var i = 0;

    		suffix = 'undefined' == typeof suffix ? '' : suffix;

    		if(typeof inputArray !== 'undefined') {
    			for( ; i < inputArray.length; i++) {
    				$(prefix + inputArray[i] + suffix)[func]();
    			}
    		}
        },

        /**
		 * Triggers WP media frame.
		 *
		 * @since 1.0
		 * @access private
		 * @method _imageUploadTrigger
         * @param {Object} button
		 */
        _imageUploadTrigger: function(button)
        {
            var uploadButton    = $(button),
                wrapper         = uploadButton.parents('.mbt-photo-field-wrapper'),
                removeButton    = wrapper.find('a.mbt-delete-img'),
                imgContainer    = wrapper.find('.mbt-img-container'),
                imgIdField      = wrapper.find('.mbt-img-id'),
                imgUrlField     = wrapper.find('.mbt-img-url');

            // Create a new media frame
            var frame = wp.media({
                title: 'Upload Photo',
                button: {
                    text: 'Use this photo'
                },
                multiple: false  // Set to true to allow multiple files to be selected
            });

            // When an image is selected in the media frame...
            frame.on( 'select', function() {

                // Get media attachment details from the frame state
                var attachment = frame.state().get('selection').first().toJSON();

                // Send the attachment URL to our custom image input field.
                imgContainer.addClass('mbt-has-img').append( '<img src="'+attachment.url+'" alt="" style="max-width:100%;"/>' );

                // Send the attachment id to our hidden input
                imgIdField.val( attachment.id );

                // Send the attachment url to our url input
                imgUrlField.val( attachment.url );

                // Hide the upload button
                uploadButton.addClass( 'hidden' );

                // Show the remove button
                removeButton.removeClass( 'hidden' );
            });

            // Finally, open the modal on click
            frame.open();
        },

        /**
		 * Remove image clicking on 'remove' button in photo field.
		 *
		 * @since 1.0
		 * @access private
		 * @method _imageRemoveTrigger
		 */
        _imageRemoveTrigger: function(button)
        {
            var removeButton    = $(button),
                wrapper         = removeButton.parents('.mbt-photo-field-wrapper'),
                uploadButton    = wrapper.find('a.mbt-upload-img'),
                imgContainer    = wrapper.find('.mbt-img-container'),
                imgIdField      = wrapper.find('.mbt-img-id'),
                imgUrlField     = wrapper.find('.mbt-img-url');

            imgContainer.html('').removeClass( 'mbt-has-img' );

            // Show the upload button
            uploadButton.removeClass( 'hidden' );

            // Hide the remove button
            removeButton.addClass( 'hidden' );

            // Delete the image id from the hidden input
            imgIdField.val( '' );

            // Delete the image url from the url input
            imgUrlField.val( '' );
        },

        /**
		 * Toggle group field content.
		 *
		 * @since 1.0
		 * @access private
		 * @method _groupFieldToggle
		 */
        _groupFieldToggle: function(handle)
        {
            var handle = $(handle);
            handle.next().slideToggle({
                duration: 0,
                complete: function() {
                    if ( $(this).is(':visible') ) {
                        handle.addClass('open');
                    } else {
                        handle.removeClass('open');
                    }
                }
            });
        },

        /**
		 * Remove group.
		 *
		 * @since 1.0
		 * @access private
		 * @method _groupFieldRemove
		 */
        _groupFieldRemove: function(button)
        {
            var groupId = $(button).data('remove-group'),
                group   = $(button).parents('.mbt-fields-group[data-group-id="'+groupId+'"]'),
                parent  = group.parent();

            group.fadeOut({
                duration: 300,
                complete: function() {
                    $(this).remove();
                    if ( parent.find('.mbt-fields-group').length === 1 ) {
                        parent.find('.mbt-fields-group .mbt-fields-group-footer .mbt-fields-group-order a').addClass('disabled');
                        parent.find('.mbt-fields-group .mbt-fields-group-footer a.mbt-fields-group-remove').addClass('disabled');
                    }
                    parent.find('.mbt-fields-group:first').find('.mbt-fields-group-up').addClass('disabled');
                    MBT._resetGroupFieldButtons(parent);
                    MBT._resetGroupFieldIds(parent.find('.mbt-fields-group'));
                }
            });
        },

        /**
		 * Clone group.
		 *
		 * @since 1.0
		 * @access private
		 * @method _groupFieldClone
		 */
        _groupFieldClone: function(button)
        {
            var groupId = $(button).data('clone-group'),
                group   = $(button).parents('.mbt-fields-group[data-group-id="'+groupId+'"]'),
                clone   = group.clone(),
                parent  = group.parent();

            clone.insertAfter(group);

            parent.find('.mbt-fields-group:first').find('.mbt-fields-group-up').addClass('disabled');

            MBT._resetGroupFieldButtons(parent);
            MBT._resetGroupFieldIds(parent.find('.mbt-fields-group'));
        },

        /**
		 * Shift group up.
		 *
		 * @since 1.0
		 * @access private
		 * @method _groupFieldMoveUp
		 */
        _groupFieldMoveUp: function(button)
        {
            var currentGroup    = $(button).parents('.mbt-fields-group'),
                previousGroup   = currentGroup.prev(),
                parent          = currentGroup.parent();

            currentGroup.insertBefore(previousGroup);

            MBT._resetGroupFieldButtons(parent);
            MBT._resetGroupFieldIds(parent.find('.mbt-fields-group'));
        },

        /**
		 * Shift group down.
		 *
		 * @since 1.0
		 * @access private
		 * @method _groupFieldMoveDown
		 */
        _groupFieldMoveDown: function(button)
        {
            var currentGroup    = $(button).parents('.mbt-fields-group'),
                nextGroup       = currentGroup.next(),
                parent          = currentGroup.parent();

            currentGroup.insertAfter(nextGroup);

            MBT._resetGroupFieldButtons(parent);
            MBT._resetGroupFieldIds(parent.find('.mbt-fields-group'));
        },

        /**
		 * Reset shift buttons.
		 *
		 * @since 1.0
		 * @access private
		 * @method _resetGroupFieldButtons
		 */
        _resetGroupFieldButtons: function(groupsParent)
        {
            groupsParent.find('.mbt-fields-group .mbt-fields-group-order a').removeClass('disabled');
            groupsParent.find('.mbt-fields-group:first').find('.mbt-fields-group-up').addClass('disabled');
            groupsParent.find('.mbt-fields-group:first').find('.mbt-fields-group-down').removeClass('disabled');
            groupsParent.find('.mbt-fields-group:last').find('.mbt-fields-group-up').removeClass('disabled');
            groupsParent.find('.mbt-fields-group:last').find('.mbt-fields-group-down').addClass('disabled');
            if ( groupsParent.find('.mbt-fields-group').length > 1 ) {
                groupsParent.find('.mbt-fields-group .mbt-fields-group-remove').removeClass('disabled');
            }
        },

        /**
		 * Reset all occurance of group ids.
		 *
		 * @since 1.0
		 * @access private
		 * @method _resetGroupFieldIds
		 */
        _resetGroupFieldIds: function(groups)
        {
            var groupId     = 1,
                nextGroupId = groups.length + 1;

            groups.each(function() {
                var group       = $(this),
                    title       = group.data('group-title'),
                    fieldName   = group.data('field-name'),
                    fieldId     = 'mbt-field-' + fieldName,
                    groupInfo   = group.find('.mbt-fields-group-info').data('info'),
                    subFields   = groupInfo.group_sub_fields;

                // Update group id.
                group.attr('data-group-id', groupId);
                // Update group title.
                group.find('.mbt-fields-group-title .mbt-group-field-title-text').html(title + ' ' + groupId);
                // Update group id in remove button.
                group.find('.mbt-fields-group-remove').attr('data-remove-group', groupId);
                // Update group id in clone button.
                group.find('.mbt-fields-group-clone').attr('data-clone-group', groupId);

                // Update sub fields.
                subFields.forEach(function(obj) {

                    // Get sub fields row.
                    var row = group.find('tr.mbt-field[id="mbt-field-' + obj.field_name + '"]');

                    row.find('[name*="'+obj.field_name+'"]').each(function() {
                        var name    = $(this).attr('name'),
                            prefix  = name.split(obj.field_name)[0],
                            suffix  = '';

                        if ( undefined === prefix ) {
                            prefix = '';
                        }
                        if ( 'photo' === row.data('type') ) {
                            if ( $(this).hasClass('mbt-img-url') ) {
                                suffix = '[url]';
                            }
                            if ( $(this).hasClass('mbt-img-id') ) {
                                suffix = '[id]';
                            }
                        }
                        name = name.replace( name, prefix + fieldName + '[' + groupId + '][' + obj.original_name + ']' + suffix );
                        $(this).attr('name', name).attr('id', name);
                    });

                    group.find('tr.mbt-field[id="mbt-field-' + obj.field_name + '"]').attr('id', fieldId + '[' + groupId + '][' + obj.original_name + ']');
                });

                groupId++;
            });
        },

        /**
		 * Ajax actions when post type dropdown changes.
		 *
		 * @since 1.0
		 * @access private
		 * @method _updatePostTypeField
		 */
        _updatePostTypeField: function(post_type, field)
        {
            var selected_taxonomy = field.data('value');
            $.ajax({
                type: 'post',
                data: {
                    action: 'mbt_get_object_taxonomies',
                    mbt_post_type: post_type,
                    mbt_tax_exclude: field.data('exclude')
                },
                url: ajaxurl,
                success: function(res) {
                    if ( res !== 'undefined' || res !== '' ) {
                        field.html(res);
                        field.find('option[value="'+selected_taxonomy+'"]').attr('selected', 'selected');
                    }
                }
            });
        }
    };

    $(document).ready(function() {
        // Initialize MBT.
        MBT._init();
    });
})(jQuery);

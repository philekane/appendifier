( function( wp ) {
    var el = wp.element.createElement;
    var registerBlockType = wp.blocks.registerBlockType;
    var TextareaControl = wp.components.TextareaControl;
    var RichText = wp.blockEditor.RichText

	/**
	 * Retrieves the translation of text.
	 * @see https://github.com/WordPress/gutenberg/tree/master/i18n#api
	 */
	var __ = wp.i18n.__;

    /**
     * Register meta block for calendar copy
     */
    registerBlockType( 'bwetc-data-structure-markup/meta-block', {

        title: __( 'Appendifier', 'data_structure_markup' ),
        description: __('Append Mark Up to <head> element'),
        icon: 'embed-generic',
        category: 'embed',
        keywords: [
            __( 'data structure', 'bwe-data-structure' ),
            __( 'google', 'bwe-data-structure' ),
            __( 'google', 'bwe-data-structure' ),
            __( 'append', 'bwe-data-structure' ),
        ],

        attributes: {
            bwetcDataStructureMarkUp: {
               	type: 'string',
				source: 'meta',
				meta : 'bwetc_data_structure_markup'
            }
		},

        edit: function( props ) {
            var className = props.className;
            var setAttributes = props.setAttributes;

            return el(
                'div',
                { className: className, padding: '20px'  },
                el( 'p', {className: 'bwetc-data-structure-description'}, __( 'Go to Google\'s Structured Data Markup Helper tool at https://www.google.com/webmasters/markup-helper/u/0/ and tag the data on your page then copy html and paste below.' ) ),	
                el( TextareaControl, {
                    label: 'Append Mark Up To <head> element ',
                    value: props.attributes.bwetcDataStructureMarkUp,
                    key: 'bwetcDataStructureMarkUpKey',
                    className: 'bwetc-data-structure-markup',
                    onChange: function( value ) {
                        setAttributes( { bwetcDataStructureMarkUp: value } );
                    }

                } ),
                el(RichText, {
                    key: 'hiddenJsonKey',
                    tagName: 'p',
                    keepplaceholderonfocus: 'true',
                    value: props.attributes.hiddenJson, // adding a p tag with hiddenJson class in order to see that data structure block was used therefore add data-structure as json-ld to head
                    className: 'hiddenJson visually-hidden'
                })
            );
    },

        // No information saved to the block except the p tag for seeing if data structure is on the page
        // Data is saved to post meta via attributes
        save: function(props) {
            var attributes = props.attributes;
           // return null;
            return (
                el(RichText.Content, {
                    tagName: 'p',
                    className: 'hiddenJson visually-hidden',
                    value: attributes.hiddenJson
                })
            )
        },
        example: {
            attributes: {
              //this shows the preview of what the block looks like.
            },
        },
	} );

} )( window.wp );

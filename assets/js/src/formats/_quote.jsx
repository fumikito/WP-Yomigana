const { Fragment } = wp.element;
const { __ } = wp.i18n;
const { toggleFormat, registerFormatType } = wp.richText;
const { RichTextToolbarButton, RichTextShortcut } = wp.editor;
const { SVG, Path } = wp.components;

registerFormatType( 'wp-yomigana/q', {

  title: __( 'Inline Quote', 'wp-yomigana' ),

  tagName: 'q',

  attributes: {
    cite: '',
  },

  className: null,

  edit ({ isActive, value, onChange }) {

    const onToggle = () => {
      let cite = '';
      if ( ! isActive ) {
        cite = window.prompt( __( 'Enter source information(optional)', 'wp-yomigana' ) ) || '';
      }
      return onChange( toggleFormat( value, {
        type: 'wp-yomigana/q',
        attributes: {
          cite: cite,
        }
      } ) );
    };

    // @see keycodes/src/index.js
    const shortcutType = 'primaryShift';
    const shortcutCharacter ='q';
    const icon = (
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 240 240">
        <path d="M75.83,128.06c-18.13-4.1-18.72-4.68-18.72-14,0-20.47,15.21-39.78,44.45-56.15l-11.69-14c-45,21.06-71.95,60.25-71.95,103.53,0,30.41,18.72,52.64,44.46,52.64,22.22,0,39.77-16.38,39.77-38A34.46,34.46,0,0,0,75.83,128.06ZM62.38,190c-20,0-34.46-17.93-34.46-42.64,0-33.31,17.34-63.48,47.31-83.8C59.89,76.11,47.11,92.89,47.11,114c0,17.75,8.81,19.79,26.35,23.75A24.47,24.47,0,0,1,92.15,162C92.15,178,79.35,190,62.38,190Zm128.09-61.94c-18.13-4.1-18.72-4.68-18.72-14,0-20.47,15.21-39.78,44.46-56.15l-11.7-14c-45,21.06-71.95,60.25-71.95,103.53,0,30.41,18.72,52.64,44.46,52.64,22.22,0,39.77-16.38,39.77-38A34.46,34.46,0,0,0,190.47,128.06ZM177,190c-20,0-34.46-17.93-34.46-42.64,0-33.31,17.34-63.48,47.31-83.8C174.53,76.11,161.75,92.89,161.75,114c0,17.75,8.81,19.79,26.35,23.75A24.47,24.47,0,0,1,206.79,162C206.79,178,194,190,177,190Z"/>
      </svg>
    );
    return (
      <Fragment>
        <RichTextShortcut type={shortcutType} character={shortcutCharacter} onUse={onToggle}  />
        <RichTextToolbarButton icon={icon} title={__( 'Inline Quotation', 'wp-yomigana' )} onClick={onToggle}
          isActive={isActive} shorcutType={shortcutType} shorcutCharacter={shortcutCharacter} />
      </Fragment>
    )
  }
} );

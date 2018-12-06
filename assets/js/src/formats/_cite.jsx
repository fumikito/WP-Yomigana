const { Fragment } = wp.element;
const { __ } = wp.i18n;
const { toggleFormat, registerFormatType } = wp.richText;
const { RichTextToolbarButton, RichTextShortcut } = wp.editor;

registerFormatType( 'wp-yomigana/cite', {

  title: __( 'Cite', 'wp-yomigana' ),

  tagName: 'cite',

  className: null,

  edit ({ isActive, value, onChange }) {

    const onToggle = () => onChange( toggleFormat( value, { type: 'wp-yomigana/cite' } ) );

    // @see keycodes/src/index.js
    const shortcutType = 'ctrlShift';
    const shortcutCharacter ='c';
    const icon = (
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 240 240">
        <path d="M28.38,64.84c0,6.6-3.74,12.06-10,12.06C12.76,76.9,9,71.44,9,64.84s4-12.09,9.73-12.09S28.33,58.05,28.38,64.84Zm-17.49,137V95H26.46V201.8Z"/><path d="M39.33,201.8c.41-7.33.71-18.05.71-27.54V45H55.59v67.12H56c5.49-12,15.46-19.56,29.34-19.56,21.38,0,36.4,22,36.32,54.37,0,38.3-19.55,57.26-38.75,57.26-12.41,0-22.33-6-28.83-20h-.41l-.8,17.55ZM55.59,159a38.2,38.2,0,0,0,.65,7c2.94,13.49,12.17,22.73,23.47,22.73,16.46,0,26.13-16.54,26.13-41.05,0-21.47-8.94-39.84-25.67-39.84-10.61,0-20.55,9.21-23.68,23.87a39.43,39.43,0,0,0-.9,8.07Z"/><path d="M146.33,64.84c0,6.6-3.73,12.06-10,12.06-5.66,0-9.46-5.46-9.45-12.06s4-12.09,9.73-12.09S146.29,58.05,146.33,64.84Zm-17.48,137V95h15.57V201.8Z"/><path d="M232.29,45V174.26c0,9.49.29,20.17.71,27.54H219.09l-.8-18.46h-.41c-4.79,11.93-15.14,20.89-29.19,20.89-20.85,0-36.93-21.87-36.93-54.25-.14-35.69,17.71-57.38,38.66-57.38,13.21,0,22.1,7.68,26,16.19h.35V45Zm-15.56,93.43a48.46,48.46,0,0,0-.66-8.3c-2.43-12.25-10.87-22.34-22.66-22.34-16.2,0-25.89,17.63-25.89,41.1,0,21.68,8.7,39.69,25.57,39.69,10.48,0,20-8.77,22.87-23.32a38.66,38.66,0,0,0,.77-8.32Z"/>
      </svg>
    );
    return (
      <Fragment>
        <RichTextShortcut type={shortcutType} character={shortcutCharacter} onUse={onToggle}  />
        <RichTextToolbarButton icon={icon} title={__( 'Cite', 'wp-yomigana' )} onClick={onToggle}
          isActive={isActive} shorcutType={shortcutType} shorcutCharacter={shortcutCharacter} />
      </Fragment>
    )
  }
} );

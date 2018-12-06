const { Fragment } = wp.element;
const { __ } = wp.i18n;
const { toggleFormat, registerFormatType } = wp.richText;
const { RichTextToolbarButton, RichTextShortcut } = wp.editor;
const { SVG, Path } = wp.components;

registerFormatType( 'wp-yomigana/small', {

  title: __( 'Annotation', 'wp-yomigana' ),

  tagName: 'small',

  className: null,

  edit ({ isActive, value, onChange }) {

    const onToggle = () => onChange( toggleFormat( value, { type: 'wp-yomigana/small' } ) );

    // @see keycodes/src/index.js
    const shortcutType = 'primaryShift';
    const shortcutCharacter ='a';
    const icon = (
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 240 240"><path d="M187.4,194.53a2.81,2.81,0,0,1-2,1,2.46,2.46,0,0,1-1.82-.81l-66.43-66.22-66,65.82a2.8,2.8,0,0,1-1.82,1,3.22,3.22,0,0,1-2-1l-1.42-1.42a2.08,2.08,0,0,1-.81-1.62c0-.61.4-1.22,1.21-2l65.82-65.81-66.42-66a3.53,3.53,0,0,1-1.22-2.23,2.08,2.08,0,0,1,.81-1.62l2-2c.81-.81,1.41-1.21,2-1.21s1.22.4,2,1.21L117.12,118,184,51.55a2.84,2.84,0,0,1,1.83-1,2.06,2.06,0,0,1,1.41.81L189,53.17a2.34,2.34,0,0,1,.6,1.62,2.48,2.48,0,0,1-.81,1.83l-66.42,66.62,66.63,66a2.52,2.52,0,0,1,.81,1.83,2.81,2.81,0,0,1-1,2ZM46,121.42a14.78,14.78,0,0,1,15-14.78,14.52,14.52,0,0,1,14.38,14.78c0,8.71-6.48,15-14.38,15A15.09,15.09,0,0,1,46,121.42Zm70.88-38.48a15.07,15.07,0,0,1-15-15,14.78,14.78,0,0,1,15-14.79c7.9,0,15.19,6.48,15.19,14.79C132.11,75.86,124.82,82.94,116.92,82.94Zm.2,110.17a15.22,15.22,0,0,1-15-15c0-8.1,7.09-14.37,15-14.37s15,6.48,15,14.37A15.22,15.22,0,0,1,117.12,193.11Zm56.71-56.5c-7.9,0-15.19-7.09-15.19-15s7.29-15,15.19-15a15,15,0,0,1,0,30Z"/></svg>
    );
    return (
      <Fragment>
        <RichTextShortcut type={shortcutType} character={shortcutCharacter} onUse={onToggle}  />
        <RichTextToolbarButton icon={icon} title={__( 'Annotation', 'wp-yomigana' )} onClick={onToggle}
          isActive={isActive} shorcutType={shortcutType} shorcutCharacter={shortcutCharacter} />
      </Fragment>
    )
  }
} );

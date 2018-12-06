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
        <path d="M78.27,121.08H54.62V105.27q0-6.88-.76-8.6c-.5-1.14-1.62-1.71-3.34-1.71-2,0-3.18.69-3.71,2.08s-.78,4.38-.78,9v42.24q0,6.63.78,8.65c.53,1.35,1.71,2,3.54,2a3.34,3.34,0,0,0,3.46-2q.81-2,.81-9.51V136H78.27v3.54q0,14.12-2,20t-8.82,10.35q-6.82,4.44-16.83,4.44-10.38,0-17.13-3.76a18.33,18.33,0,0,1-8.93-10.42q-2.19-6.66-2.19-20V113.55a118.17,118.17,0,0,1,.67-14.74,20.67,20.67,0,0,1,4-9.47,22.43,22.43,0,0,1,9.27-7.16A33.49,33.49,0,0,1,50,79.57q10.45,0,17.24,4t8.94,10.07q2.13,6,2.13,18.76Z"/>
        <path d="M110.4,81.48v90.94H86.75V81.48Z"/>
        <path d="M167.52,81.48v18.2h-14v72.74H129.83V99.68h-14V81.48Z"/>
        <path d="M172.92,81.48h39.43v18.2H196.56v17.24h14.78v17.3H196.56v20h17.36v18.2h-41Z"/>
        <path d="M181.71,37.85v5.69h-2.08V69.18h2.08v5.7H171.26v-37Z"/>
        <path d="M198.45,37.85v37h-9.24V55a36.43,36.43,0,0,0-.2-5.17,2,2,0,0,0-1.13-1.32,11.76,11.76,0,0,0-4.13-.44h-.92V43.78q6.71-1.44,10.18-5.93Z"/>
        <path d="M201.22,74.88v-5.7h2.08V43.54h-2.08V37.85h10.46v37Z"/>
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

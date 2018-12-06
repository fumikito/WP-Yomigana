const { Fragment } = wp.element;
const { __ } = wp.i18n;
const { toggleFormat, registerFormatType, insert, applyFormat } = wp.richText;
const { RichTextToolbarButton, RichTextShortcut } = wp.editor;
const { SVG, Path } = wp.components;

registerFormatType( 'wp-yomigana/rt', {

  title: __( 'Ruby Character', 'wp-yomigana' ),

  tagName: 'rt',

  className: null,

  edit( {isActive, value, onChange} ) {
    return <Fragment></Fragment>;
  }

} );

registerFormatType( 'wp-yomigana/ruby', {

  title: __( 'Ruby', 'wp-yomigana' ),

  tagName: 'ruby',

  className: null,

  edit ({ isActive, value, onChange }) {

    const onToggle = () => {
      let ruby = '';
      if ( ! isActive ) {
        ruby = window.prompt( __( 'Enter ruby characters', 'wp-yomigana' ) ) || value.text.substr( value.start, value.end -value.start );
        const rubyEnd   = value.end;
        const rubyStart = value.start;
        value = insert( value, ruby, rubyEnd );
        value.start = rubyStart;
        value.end   = rubyEnd + ruby.length;
        value = applyFormat( value, {
          type: 'wp-yomigana/ruby'
        }, rubyStart, rubyEnd + ruby.length );
        value = applyFormat( value, {
          type: 'wp-yomigana/rt'
        }, rubyEnd, rubyEnd + ruby.length );
      } else {
        value = toggleFormat( value, {
          type: 'wp-yomigana/ruby'
        } );
      }
      return onChange( value );
    };

    // @see keycodes/src/index.js
    const shortcutType = 'primaryShift';
    const shortcutCharacter ='r';
    const icon = (
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 240 240">
        <path d="M50,85.67c-.49,5.22-.49,5.22-1.35,35.65-.74,32.11-3.2,49.17-9.11,65.92-4.06,11.37-8.73,19.67-17.09,30.74-6.28-10.3-9.72-14.6-17.6-21.52,7-7.84,10.83-14.29,14.64-24.43,5.78-15.06,7.75-34.12,7.75-73.92A140.44,140.44,0,0,0,26.75,84Zm34.81-2.46a137.9,137.9,0,0,0-1,17.82l-.61,80.53A53.72,53.72,0,0,0,98,164.65a87.85,87.85,0,0,0,9.59-26.27c6.15,9.52,9.35,13.52,16.36,20.28-5.53,17.21-11.93,29-21.28,39-7.38,7.84-13.41,11.83-25.83,17.52-3.45,1.54-4.43,2-6,3.23L60.21,204.61c1.11-4,1.35-6.61,1.35-12.91l.62-91V95.19a113.53,113.53,0,0,0-.49-12Z"/><path d="M222.59,124.39c-3,1.84-3.2,2-6.15,3.69C198.11,139.3,180,146.67,157.89,151.9v17.21c0,9.68,0,9.68.49,11.83.74,2.92,2.34,4.15,5.9,4.77a87.47,87.47,0,0,0,13,.92,215.5,215.5,0,0,0,24-1.54c9.1-1.23,13.9-2.3,21.16-4.92l.86,28.43a104.88,104.88,0,0,0-12.67,1.08,353.21,353.21,0,0,1-35.92,2.3c-16.85,0-25.34-1.84-31-6.76-3.81-3.53-6-8.6-6.89-16.6-.37-3.07-.49-7.52-.49-16.28V105.49a196,196,0,0,0-.74-20.44h23c-.61,4.76-.73,8.61-.73,20.59v20.75c14.76-3.69,34-12.6,46.86-22a101.82,101.82,0,0,0-8.36-18.59l12.18-4.92a114.82,114.82,0,0,1,10.21,23.51l-6.15,2.77Zm3.2-50.4A119,119,0,0,1,236,96.88l-11.69,5.38a133.73,133.73,0,0,0-10.08-23.51Z"/>
        <circle cx="59" cy="47" r="23"/>
        <circle cx="179" cy="47" r="23"/>
      </svg>
    );
    return (
      <Fragment>
        <RichTextShortcut type={shortcutType} character={shortcutCharacter} onUse={onToggle}  />
        <RichTextToolbarButton icon={icon} title={__( 'Ruby', 'wp-yomigana' )} onClick={onToggle}
                               isActive={isActive} shorcutType={shortcutType} shorcutCharacter={shortcutCharacter} />
      </Fragment>
    )
  }
} );

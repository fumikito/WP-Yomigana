const { registerBlockType } = wp.blocks;
const { RichText }          = wp.editor;
const { __ }                = wp.i18n;


registerBlockType( 'wp-yomigana/term', {

  title:  __( 'Term', 'wp-yomigana' ),

  icon: (
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 240 240"><title>block-icons</title><path d="M49.46,38.07,62.65,23.41,53.27,75.59H36.85L39.2,23.41h160l2.35,52.18H185.17l-9.38-52.18L189,38.07Zm72.69,165.61,41,4.4v11.73H75.25V208.08l41-4.4ZM106,114.28c0-30.19,0-60.67-.88-90.87h28.14c-.88,29.61-.88,60.09-.88,90.87v14.66c0,30.19,0,60.67.88,90.87H105.15c.88-29.61.88-60.09.88-90.87Z"/></svg>
  ),

  category: 'common',

  parent: [ 'wp-yomigana/dl' ],

  attributes: {
    content: {
      source: 'html',
      selector: 'dt'
    }
  },

  edit({attributes, setAttributes, className}){
    return (
      <RichText className={className} tagName='div' value={attributes.content} onChange={(content)=>setAttributes({content})}/>
    );
  },

  save({attributes}){
    return (
      <RichText.Content tagName='dt' value={attributes.content} />
    )
  }

} );
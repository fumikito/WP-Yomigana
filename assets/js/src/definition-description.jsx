const { registerBlockType } = wp.blocks;
const { RichText } = wp.editor;
const { __ } = wp.i18n;



registerBlockType( 'wp-yomigana/description', {

  title: __( 'Description', 'wp-yomigana' ),

  icon: (
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 240 240"><path d="M28.71,33.65V21.49H71.27V38.21h-3ZM71.27,208.44v16.72H28.71V213l39.52-4.56ZM57.59,115.73c0-31.31,0-62.93-.91-94.24H85.86C85,52.19,85,83.81,85,115.73v11.55c0,35,0,66.57.91,97.88H56.68c.91-30.7.91-62.32.91-97.88Zm13.68,95.45h39.21c47.73,0,73.87-32.23,73.87-87.55,0-57.45-26.14-88.16-71.13-88.16H71.27v-14h47.12c57.76,0,97.88,35.87,97.88,102.14,0,65.66-45,101.53-104,101.53h-41Z"/></svg>
  ),

  category: 'common',

  parent: [ 'wp-yomigana/dl' ],

  attributes: {
    content: {
      source: 'html',
      selector: 'dd'
    }
  },

  edit({attributes, setAttributes, className}){
    return (
      <RichText className={className} tagName='div' value={attributes.content} onChange={(content)=>setAttributes({content})}/>
    );
  },

  save({attributes}){
    return (
      <RichText.Content tagName='dd' value={attributes.content} />
    )
  }

} );
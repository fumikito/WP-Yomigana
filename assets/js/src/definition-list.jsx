const { registerBlockType } = wp.blocks;
const { InnerBlocks } = wp.editor;

/* global YomiganaDl: false */

registerBlockType( 'wp-yomigana/dl', {

  title: YomiganaDl.label,

  icon: (
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 240 240">
      <rect x="20" y="21.5" width="100" height="30"/>
      <rect x="20" y="60" width="200" height="20"/>
      <rect x="20" y="91.5" width="100" height="30"/>
      <rect x="20" y="130" width="200" height="20"/>
      <rect x="20" y="161.5" width="100" height="30"/>
      <rect x="20" y="200" width="200" height="20"/>
    </svg>
  ),

  category: 'common',

  keywords: [],

  edit({attributes, className}){
    const allowedBlocks = [ 'wp-yomigana/term', 'wp-yomigana/description' ];
    return (
      <div className={className}>
        <InnerBlocks allowedBlocks={allowedBlocks} templateLock={false} />
      </div>
    )
  },

  save({className}){
    return (
      <dl className={className}>
        <InnerBlocks.Content />
      </dl>
    )
  }
} );

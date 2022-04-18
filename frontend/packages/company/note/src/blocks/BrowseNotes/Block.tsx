/**
 * @type: block
 * name: note.block.BrowseBlogs
 * title: Browse Notes
 */
import { createBlock, ListViewBlockProps } from '@metafox/core';

export default createBlock<ListViewBlockProps>({
  extendBlock: 'core.block.listview',
  overrides: {
    contentType: 'note',
    dataSource: { apiUrl: '/note' },
    itemProps: { showActionMenu: true }
  },
  defaults: {
    title: 'Notes',
    itemView: 'note.itemView.mainCard',
    blockLayout: 'Main Listings',
    gridLayout: 'Note - Main Cards'
  }
});

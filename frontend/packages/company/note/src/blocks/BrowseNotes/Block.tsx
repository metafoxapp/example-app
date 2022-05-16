/**
 * @type: block
 * name: note.block.BrowseNotes
 * title: Browse Notes
 */
import { createBlock, ListViewBlockProps } from '@metafox/framework';

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
    gridLayout: 'Blog - Main Cards'
  }
});

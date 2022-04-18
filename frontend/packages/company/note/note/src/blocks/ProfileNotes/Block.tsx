/**
 * @type: block
 * name: note.block.ProfileNotes
 * title: Profile Notes
 * description: display Profile Notes
 */
import { createBlock, ListViewBlockProps } from '@metafox/core';

export default createBlock<ListViewBlockProps>({
  name: 'ProfileNotes',
  extendBlock: 'core.block.listview',
  overrides: {
    headerActions: [
      {
        label: 'Add New Note',
        to: '/note/add?owner_id=:id',
        showWhen: [
          'or',
          ['truthy', 'isAuthUser'],
          ['neq', 'pageParams.module_name', 'user']
        ]
      }
    ]
  },
  defaults: {
    contentType: 'note',
    dataSource: {
      apiUrl: '/note'
    },
    title: 'Notes',
    itemView: 'note.itemView.mainCard'
  }
});

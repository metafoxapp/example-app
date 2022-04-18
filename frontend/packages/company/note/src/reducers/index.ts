/**
 * @type: reducer
 * name: note
 */

import {
  combineReducers,
  createEntityReducer,
  createResourceConfigReducer,
  createUIReducer
} from '@metafox/core';
import { APP_NAME } from '../constants';

export default combineReducers({
  entities: createEntityReducer(APP_NAME),
  resourceConfig: createResourceConfigReducer(APP_NAME),
  uiConfig: createUIReducer(APP_NAME, {
    sidebarHeader: {
      homepageHeader: {
        title: 'Notes',
        to: '/note',
        icon: 'ico-newspaper-alt'
      }
    },
    sidebarCategory: {
      dataSource: { apiUrl: '/note-category' },
      href: '/note/category',
      title: 'Categories'
    },
    sidebarSearch: {
      placeholder: 'Search notes'
    },
    menus: {}
  })
});

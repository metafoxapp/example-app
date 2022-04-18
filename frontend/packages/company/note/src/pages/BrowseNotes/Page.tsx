/**
 * @type: route
 * name: note.browse
 * path: /note/:tab(friend|all|pending|feature|spam|draft)
 */
import { createBrowseItemPage } from '@metafox/core';

export default createBrowseItemPage({
  appName: 'note',
  resourceName: 'note',
  pageName: 'note.browse',
  categoryName: 'blog_category'
});

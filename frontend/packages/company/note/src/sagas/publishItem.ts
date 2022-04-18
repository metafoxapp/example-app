/**
 * @type: saga
 * name: note.saga.publishItem
 */

import {
  getGlobalContext,
  getItem,
  getItemActionConfig,
  handleActionConfirm,
  handleActionError,
  handleActionFeedback,
  ItemLocalAction,
  PAGINATION_REFRESH
} from '@metafox/core';
import { put, takeEvery } from 'redux-saga/effects';

function* publishItem(action: ItemLocalAction) {
  const { identity } = action.payload;
  const item = yield* getItem(identity);

  if (!item) return;

  const { apiClient, compactUrl } = yield* getGlobalContext();

  const config = yield* getItemActionConfig(item, 'publishBlog');

  if (!config.apiUrl) return;

  const ok = yield* handleActionConfirm(config);

  if (!ok) return;

  try {
    const response = yield apiClient.request({
      method: config.apiMethod,
      url: compactUrl(config.apiUrl, item)
    });

    yield put({
      type: '@updatedItem/note',
      payload: { id: item.id },
      meta: {}
    });

    yield put({
      type: PAGINATION_REFRESH,
      payload: {
        apiUrl: '/note?view=draft',
        apiParams: {},
        pagingId: '/note?view=draft'
      }
    });

    yield* handleActionFeedback(response);
  } catch (error) {
    yield* handleActionError(error);
  }
}

const sagas = [takeEvery('publishItem', publishItem)];

export default sagas;

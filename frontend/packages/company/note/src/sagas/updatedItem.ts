/**
 * @type: saga
 * name: note.saga.updatedItem
 */

import { LocalAction, viewItem } from '@metafox/framework';
import { takeEvery } from 'redux-saga/effects';

function* updatedItem({ payload: { id } }: LocalAction<{ id: string }>) {
  yield* viewItem('note', 'note', id);
}

const sagas = [takeEvery('@updatedItem/note', updatedItem)];

export default sagas;

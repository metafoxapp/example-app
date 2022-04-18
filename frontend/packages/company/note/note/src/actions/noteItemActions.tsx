import { HandleAction } from '@metafox/core';
import { NoteItemActions } from '../types';

export default function noteItemActions(
  dispatch: HandleAction
): NoteItemActions {
  return {
    deleteItem: () => dispatch('deleteItem'),
    approveItem: () => dispatch('approveItem')
  };
}

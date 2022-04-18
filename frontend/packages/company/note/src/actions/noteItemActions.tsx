import { HandleAction } from '@metafox/core';
import { BlogItemActions } from '../types';

export default function blogItemActions(
  dispatch: HandleAction
): BlogItemActions {
  return {
    deleteItem: () => dispatch('deleteItem'),
    approveItem: () => dispatch('approveItem')
  };
}

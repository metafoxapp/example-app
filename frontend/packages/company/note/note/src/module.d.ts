import '@metafox/core/Manager';
import { AppState } from './types';

declare module '@metafox/core/Manager' {
  interface GlobalState {
    note?: AppState;
  }
}

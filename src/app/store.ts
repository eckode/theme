import { configureStore, ThunkAction, Action } from '@reduxjs/toolkit';
import content from '../components/content/slice';

export const store = configureStore({
  reducer: {
    content,
  },
});

export type AppDispatch = typeof store.dispatch;
export type RootState = ReturnType<typeof store.getState>;
export type AppThunk<ReturnType = void> = ThunkAction<
  ReturnType,
  RootState,
  unknown,
  Action<string>
>;

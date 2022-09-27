import { configureStore, ThunkAction, Action } from '@reduxjs/toolkit';
import counter from '../blocks/counter/counterSlice';
import content from '../components/content-type/contentSlice';

export const store = configureStore({
  reducer: {
    counter,
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

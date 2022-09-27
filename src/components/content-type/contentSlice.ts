import { createAsyncThunk, createSlice, PayloadAction } from "@reduxjs/toolkit";
import { RootState } from "../../app/store";
import { fetchContent } from "./contentAPI";

import * as ContentType from "./ContentType.d";

const initialState: ContentType.State = {
  types: {},
  status: "idle",
};

export const contentFetchAsync = createAsyncThunk(
  "types/fetchContent",
  async (
    endpoint: ContentType.Endpoint,
    {
      getState,
      rejectWithValue,
    }: { getState: Function; rejectWithValue: Function },
  ) => {
    const state = getState();
    if (state.content.types[endpoint] ?? false) {
      return rejectWithValue([endpoint, "exists"]);
    }
    try {
      return await fetchContent(endpoint);
    } catch (err) {
      console.error(err);
      return rejectWithValue(err);
    }
  },
);

export const contentSlice = createSlice({
  name: "content",
  initialState,
  reducers: {
    get: (state, action: PayloadAction<number>) => {
      // state.value += action.payload;
    },
  },
  extraReducers: (builder) => {
    builder
      .addCase(contentFetchAsync.pending, (state) => {
        state.status = "loading";
      })
      .addCase(
        contentFetchAsync.fulfilled,
        (state, { payload: [endpoint, content] }) => {
          state.status = "idle";

          if (content === "exists") {
            return;
          }

          const newContents: ContentType.IdRow = content.reduce(
            (acc: ContentType.IdRow, item: ContentType.Model) => {
              acc[item.id] = item;
              return acc;
            },
            {},
          );

          state.types[endpoint] = {
            ...(state.types[endpoint] ?? {}),
            ...newContents,
          };
        },
      )
      .addCase(contentFetchAsync.rejected, (state) => {
        state.status = "failed";
      });
  },
});

export const { get } = contentSlice.actions;

// The function below is called a selector and allows us to select a value from
// the state. Selectors can also be defined inline where they're used instead of
// in the slice file. For example: `useSelector((state: RootState) => state.counter.value)`
export const selectCount = (state: RootState) => state.counter.value;

export default contentSlice.reducer;

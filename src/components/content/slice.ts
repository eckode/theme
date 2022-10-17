import { createAsyncThunk, createSlice, PayloadAction } from "@reduxjs/toolkit";
import { ContentModel } from "../../types";
import { apiFetch } from "../../utils/fetch";
import { addStaticPayload } from "../../utils/store";

import * as ContentType from "./types";

const initialState: ContentType.State = addStaticPayload({
  types: {},
  all: {},
  status: "idle",
  boot: true,
});
export const contentFetchAsync = createAsyncThunk(
  "types/fetchContent",
  async (
    { endpoint, page = 1 }: ContentType.FetchAsyncArgs,
    { rejectWithValue }: { getState: Function; rejectWithValue: Function },
  ) => {
    const response: ContentType.FetchAsyncResponse = {
      page: 0,
      endpoint,
      payload: [],
    };

    try {
      response.payload = await apiFetch(endpoint, {}, { page });
      return response;
    } catch (err) {
      console.error(err);
      return rejectWithValue([endpoint, err]);
    }
  },
);

export const contentSlice = createSlice({
  name: "content",
  initialState,
  reducers: {
    isBoot: (state, { payload }: PayloadAction<boolean>) => {
      state.boot = payload;
    },
  },
  extraReducers: (builder) => {
    builder
      .addCase(contentFetchAsync.pending, (state) => {
        state.status = "loading";
      })
      .addCase(
        contentFetchAsync.fulfilled,
        (
          state,
          {
            payload: { endpoint, payload },
          }: { payload: ContentType.FetchAsyncResponse },
        ) => {
          if (payload.length < 1) {
            return;
          }

          const newContents = payload.reduce(
            (acc: ContentType.AllObject, item: ContentModel) => {
              acc[item.id] = item;
              return acc;
            },
            {},
          );

          state.all ??= {};
          state.types[endpoint] ??= [];

          state.types[endpoint].push(
            Object.keys(newContents).map((contentId) => Number(contentId)),
          );

          state.all = {
            ...state.all,
            ...newContents,
          };

          state.status = "idle";
        },
      )
      .addCase(contentFetchAsync.rejected, (state) => {
        state.status = "failed";
      });
  },
});

export const { isBoot } = contentSlice.actions;

export default contentSlice.reducer;

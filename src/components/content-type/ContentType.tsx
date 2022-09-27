import React from "react";
import { useAppDispatch } from "../../app/hooks";
import { contentFetchAsync } from "./contentSlice";

const Content = () => {
  const dispatch = useAppDispatch();
  return (
    <button onClick={() => dispatch(contentFetchAsync("posts?page=1"))}>
      Add Async
    </button>
  );
};

export default Content;

import React, { useEffect } from "react";
// import { useAppDispatch, useAppSelector } from "../../app/hooks";
// import { contentFetchAsync } from "./contentSlice";

//import * as ContentType from "./types";

const { posts = [] } = window.Eckode.boot.props;

const ContentType = (): JSX.Element => {
  // const dispatch = useAppDispatch();
  // const { all = [], status } = useAppSelector((state) => state.content);

  useEffect(() => {
    // dispatch(contentFetchAsync({ endpoint: "posts", page: 1 }));
  }, []);

  return (
    <div className="eckode__content-type">
      <code>
        <pre>{JSON.stringify({...posts}, null, 2)}</pre>
      </code>
      {posts !== undefined && Object.keys(posts).map((id) => (
        <code key={id}>
          <pre>{JSON.stringify(posts[Number(id)].title, null, 2)}</pre>
        </code>
      ))}
    </div>
  );
};

export default ContentType;

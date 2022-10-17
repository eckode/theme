import React, { useEffect } from "react";
import { Link } from "react-router-dom";
import { ContextValues } from "../../types";
import { useAppDispatch, useAppSelector } from "../../app/hooks";
import { contentFetchAsync } from "./slice";
import { State } from "./types";
import { shallowEqual } from "react-redux";

//import * as ContentType from "./types";

const shallowEqualState = (newState: any, prevState: any) => {
  const isEqual = shallowEqual(newState, prevState);
  console.log(isEqual);
  return isEqual;
};

const Content = ({ type = "post" }: { type: ContextValues }): JSX.Element => {
  const dispatch = useAppDispatch();

  useEffect(() => {
    if (contentIds.length === 0) {
      console.log("CONTENT DISPATCHING", contentIds.length);
      dispatch(contentFetchAsync({ endpoint: "post", page: 1 }));
    }
  }, []);

  // const state = useAppSelector(
  //   (state) => state.content,
  //   (newState, prevState) => {
  //     console.log(shallowEqual(newState.all, prevState.all), shallowEqual(newState.types, prevState.types));
  //     return shallowEqual(newState.all, prevState.all) && shallowEqual(newState.types, prevState.types);
  //   },
  // );

  const all = useAppSelector(({ content: { all } }) => all, shallowEqualState);
  const types = useAppSelector(
    ({ content: { types } }) => types,
    shallowEqualState,
  );

  const [contentIds] = types[type] ?? [[]];

  console.log("CONTENT RENDER", contentIds.length);

  return (
    <div className="eckode__content-type">
      {contentIds.length > 0 &&
        contentIds.map((id) => {
          return (
            <div key={id}>
              <h3>{all[id].title}</h3>
              {typeof all[id].props.excerpt !== "undefined" && (
                <p>{all[id].props.excerpt}</p>
              )}
              <Link to={all[id].path} state={all[id]}>
                Read More
              </Link>
            </div>
          );
        })}
    </div>
  );
};

export default Content;

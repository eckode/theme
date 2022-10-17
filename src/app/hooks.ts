import React, { useEffect, useState } from "react";
import { TypedUseSelectorHook, useDispatch, useSelector } from "react-redux";
import {
  useLocation,
  useParams,
  Location as ReactLocation,
} from "react-router-dom";
import {
  ContentModel,
  Contexts,
  ContextValues,
  DbId,
  MenuModel,
} from "../types";
import type { RootState, AppDispatch } from "./store";
import { isBoot as setIsBoot } from "../components/content/slice";
import { unTrailingSlashIt, unLeadingSlashIt } from "../utils";

const { boot } = window.Eckode;

// Use throughout your app instead of plain `useDispatch` and `useSelector`
export const useAppDispatch = () => useDispatch<AppDispatch>();
export const useAppSelector: TypedUseSelectorHook<RootState> = useSelector;

interface LocationModel extends ReactLocation {
  state: MenuModel;
}

/**
 * Use path returns current react-router path & element for <Route /> components.
 *
 * @returns [string, string]
 */
export const useRouteElement = () => {
  const dispatch = useAppDispatch();
  const { state, pathname }: LocationModel = useLocation();
  const [element, setElement] = useState("");

  const isBoot = unTrailingSlashIt(unLeadingSlashIt(pathname)) === boot.path;
  const context = state?.context ?? (isBoot ? boot.context : "not_found");

  useEffect(() => {
    console.log('HOOK:', context);
    setElement(context);
    // return () => {
    //   setElement("not_found");
    // };
  }, [setElement, context]);

  useEffect(() => {
    dispatch(setIsBoot(isBoot));
  }, [setIsBoot, dispatch, isBoot]);

  return element;
};

export const useContent = (
  id: DbId,
  context: Contexts,
  contextValue: ContextValues,
): ContentModel => {
  return {
    id: 0,
    context: "single",
    context_value: "post",
    title: "",
    path: "",
    content: "",
    props: {
      id: 0,
      context: "single",
      context_value: "post",
      breadcrumb: [],
    },
  };
};

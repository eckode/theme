import React, { useEffect, useState } from "react";
import { TypedUseSelectorHook, useDispatch, useSelector } from "react-redux";
import { useLocation, useParams } from "react-router-dom";
import { MenuModel } from "../types";
import type { RootState, AppDispatch } from "./store";

const { boot } = window.Eckode;

// Use throughout your app instead of plain `useDispatch` and `useSelector`
export const useAppDispatch = () => useDispatch<AppDispatch>();
export const useAppSelector: TypedUseSelectorHook<RootState> = useSelector;

// type NewType = [string, null | JSX.Element];

/**
 * Use path returns current react-router path & element for <Route /> components.
 *
 * @returns [string, string]
 */
export const useRouteElement = () => {
  const { state } : { state: MenuModel | null } = useLocation();
  const { "*": splat = "" } = useParams();
  const [element, setElement] = useState("");

  const isBoot = splat === boot.path;
  const context = state?.context ?? (isBoot ? boot.context : "not_found");

  console.groupCollapsed('Location:');
  console.log(state);
  console.groupEnd();

  useEffect(() => {
    setElement(context);
    return () => {
      setElement("not_found");
    };
  }, [setElement, context]);

  return element;
};

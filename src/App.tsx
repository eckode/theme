import React from "react";
import {
  createBrowserRouter,
  RouterProvider,
  createRoutesFromElements,
  Route,
  //Routes,
} from "react-router-dom";
import Layout from "./components/Layout";
//CSS
import "./App.css";

const router = createBrowserRouter(
  createRoutesFromElements(<Route path="*" element={<Layout />} />),
);

const Router: React.FC = (): JSX.Element => {
  return <RouterProvider router={router} />;
};

export default Router;

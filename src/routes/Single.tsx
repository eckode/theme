import React from "react";
import { useLocation, useParams } from "react-router-dom";
import { useAppSelector } from "../app/hooks";

const Single = (): JSX.Element => {
  const params = useParams();
  const location = useLocation();
  const { all, boot } = useAppSelector((state) => state.content);
  return (
    <div>
      <h1>Single</h1>
      <p>{boot ? "boot": "transition"}</p>
      {JSON.stringify({ params, location }, null, 2)}
    </div>
  );
};

export default Single;

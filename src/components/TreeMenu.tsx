import React from "react";
import { NavLink } from "react-router-dom";
import styled from "styled-components";
// Types
import { MenuModel } from "../types";

const List = styled.ul`
  background-color: #f0f0f0;
`;

const TreeMenu = ({ items }: { items: Array<MenuModel> }): JSX.Element => {
  return (
    <List>
      {items.map(
        ({
          id,
          path,
          title,
          props,
          children = [],
          props: { classes = [], contextValue, target },
        }) => (
          <li key={`menu-item__${path}_${id}`}>
            {props.context === "external" ? (
              <a
                className={classes.join(" ")}
                href={contextValue}
                target={target}
                title={title}
              >
                {title}
              </a>
            ) : (
              <NavLink
                state={{ ...props, path }}
                className={({ isActive }) =>
                  `${isActive ? "active " : ""}${classes.join(" ")}`
                }
                to={`${path}${"/" !== path ? "/" : ""}`}
              >
                {title}
              </NavLink>
            )}
            {0 !== children.length && <TreeMenu items={children} />}
          </li>
        ),
      )}
    </List>
  );
};

export default TreeMenu;

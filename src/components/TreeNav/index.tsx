import React, { Fragment } from "react";
import { NavLink } from "react-router-dom";
// Types
import { MenuModel } from "../../types";
import { Ul, Nav } from "./styled";

const TreeNav = ({
  items,
  isChild = false,
  "aria-label": ariaLabel,
}: {
  items: Array<MenuModel>;
  "aria-label"?: string;
  isChild?: boolean;
}): JSX.Element => {
  const Component = isChild ? Fragment : Nav;
  const attributes = !isChild ? { "aria-label": ariaLabel } : {};
  return (
    <Component {...attributes}>
      <Ul>
        {items.length > 0 ? (
          items.map(
            ({
              id,
              path,
              title,
              props,
              children = [],
              props: {
                excerpt,
                classes = [],
                context_value: contextValue,
                target,
              },
            }) => (
              <li key={`menu-item__${path}_${id}`}>
                {props.context === "external" ? (
                  <a
                    className={classes.join(" ")}
                    href={contextValue}
                    target={target}
                    title={title}
                    aria-label={excerpt ?? title}
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
                    aria-label={excerpt ?? title}
                  >
                    {title}
                  </NavLink>
                )}
                {0 !== children.length && <TreeNav isChild items={children} />}
              </li>
            ),
          )
        ) : (
          <li>No menu found!</li>
        )}
      </Ul>
    </Component>
  );
};

export default TreeNav;

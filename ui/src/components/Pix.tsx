import React from "react";
import { imageUrl } from "../lib/moodle";

const Pix = ({
  id,
  component = "block_xp",
  className,
  alt = "",
}: {
  id: string;
  component?: string;
  className?: string;
  alt?: string;
}) => {
  return <img src={imageUrl(id, component)} alt={alt} className={className} />;
};

export default Pix;

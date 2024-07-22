import React, { useState } from 'react';
export default function (props) {
  const [img, setImg] = useState(0);
  return /*#__PURE__*/React.createElement("div", {
    className: "relative h-32 flex justify-center"
  }, /*#__PURE__*/React.createElement("img", {
    className: "object-contain h-full w-full",
    src: props.images[img]
  }), /*#__PURE__*/React.createElement("p", {
    class: "absolute p-1 top-0 left-0"
  }, img + 1, " / ", props.images.length), /*#__PURE__*/React.createElement("button", {
    class: "absolute p-1 left-0 top-1/2 transform -translate-y-1/2",
    onClick: () => setImg(prev => (prev - 1) % props.images.length)
  }, "\u276E"), /*#__PURE__*/React.createElement("button", {
    class: "absolute p-1 right-0 top-1/2 transform -translate-y-1/2",
    onClick: () => setImg(prev => (prev + 1) % props.images.length)
  }, "\u276F"));
}
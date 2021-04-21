import Quill from "quill";
const Block = Quill.import("blots/block");

class LMS extends Block {
  static create(value) {
    if (!value) return super.create(false);

    let node = super.create(value);
    node.setAttribute("class", `lms-${value}`);
    return node;
  }

  static formats(domNode) {
    if (
      domNode.getAttribute("class") &&
      domNode.getAttribute("class").indexOf("lms-") > -1
    ) {
      return domNode.getAttribute("class").substr(4);
    } else {
      return super.formats(domNode);
    }
  }
}
LMS.blotName = "lms";
LMS.tagName = "DIV";

export default LMS;

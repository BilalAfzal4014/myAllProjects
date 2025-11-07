import { expect, test } from "vitest";
import { execute, type Rule } from "./rules";

test("returns true (simple)", () => {
  const rule: Rule = {
    type: "condition",
    path: "a",
    operator: "eq",
    value: 1,
  };

  const facts = {
    a: 1,
  };

  expect(execute(rule, facts)).toBe(true);
});

test("returns true (all)", () => {
  const rule: Rule = {
    type: "all",
    all: [
      { type: "condition", path: "a", operator: "eq", value: 1 },
      { type: "condition", path: "b", operator: "eq", value: 2 },
    ],
  };

  const facts = {
    a: 1,
    b: 2,
  };

  expect(execute(rule, facts)).toBe(true);
});

test("returns false", () => {
  const rule: Rule = {
    type: "all",
    all: [
      { type: "condition", path: "a", operator: "eq", value: 1 },
      { type: "condition", path: "b", operator: "eq", value: 2 },
    ],
  };

  const facts = {
    a: 1,
    b: 4,
  };

  expect(execute(rule, facts)).toBe(false);
});

test("not equals", () => {
  const rule: Rule = {
    type: "any",
    any: [{ type: "condition", path: "foo", operator: "notEq", value: 1 }],
  };

  const facts = {
    foo: "not 1",
  };

  expect(execute(rule, facts)).toBe(true);
});

test("nested paths", () => {
  const rule: Rule = {
    type: "any",
    any: [
      { type: "condition", path: "foo.bar", operator: "eq", value: 1 },
      { type: "condition", path: "foo.baz", operator: "eq", value: 2 },
    ],
  };

  const facts = {
    foo: {
      bar: 1,
      baz: 2,
    },
  };

  expect(execute(rule, facts)).toBe(true);
});

test("nested rules", () => {
  const rule: Rule = {
    type: "any",
    any: [
      {
        type: "all",
        all: [
          { type: "condition", path: "example", operator: "eq", value: true },
        ],
      },
      { type: "condition", path: "foo.bar", operator: "eq", value: 1 },
      { type: "condition", path: "foo.baz", operator: "eq", value: 2 },
    ],
  };

  const facts = {
    example: true,
    foo: {
      bar: 1,
      baz: 2,
    },
  };

  expect(execute(rule, facts)).toBe(true);
});

test("array facts", () => {
  const rule: Rule = {
    type: "all",
    all: [
      { type: "condition", path: "foo.0", operator: "eq", value: 1 },
      { type: "condition", path: "foo.1", operator: "eq", value: 2 },
    ],
  };

  const facts = {
    foo: [1, 2],
  };

  expect(execute(rule, facts)).toBe(true);
});

test("lt", () => {
  const rule: Rule = {
    type: "condition",
    path: "foo",
    operator: "lt",
    value: 2,
  };

  const facts = {
    foo: 1,
  };

  expect(execute(rule, facts)).toBe(true);
});

test("lt should return false for non-number value", () => {
  const rule: Rule = {
    type: "condition",
    path: "foo",
    operator: "lt",
    value: "xxx",
  };

  const facts = {
    foo: 1,
  };

  expect(execute(rule, facts)).toBe(false);
});

test("lte", () => {
  const rule: Rule = {
    type: "condition",
    path: "foo",
    operator: "lte",
    value: 1,
  };

  const facts = {
    foo: 1,
  };

  expect(execute(rule, facts)).toBe(true);
});

test("in", () => {
  const rule: Rule = {
    type: "condition",
    path: "foo",
    operator: "in",
    value: [1, 2],
  };

  const facts = {
    foo: 1,
  };

  // fact must be included in value (an array)
  expect(execute(rule, facts)).toBe(true);
});

test("not in", () => {
  const rule: Rule = {
    type: "condition",
    path: "foo",
    operator: "notIn",
    value: [1, 2],
  };

  const facts = {
    foo: 3,
  };

  expect(execute(rule, facts)).toBe(true);
});

// test("contains", () => {
//   const rule: Rule = {
//     type: "condition",
//     path: "foo",
//     operator: "contains",
//     value: 1,
//   };

//   const facts = {
//     foo: [1, 2],
//   };

//   // fact (an array) must include value
//   expect(execute(rule, facts)).toBe(true);
// });

// test("doesNotContain", () => {
//   const rule: Rule = {
//     type: "condition",
//     path: "foo",
//     operator: "doesNotContain",
//     value: 3,
//   };

//   const facts = {
//     foo: [1, 2],
//   };

//   expect(execute(rule, facts)).toBe(true);
// });

// Additional test ideas:
// - test for nested paths with arrays
// - validate a rule schema

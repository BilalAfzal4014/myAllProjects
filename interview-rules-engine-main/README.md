# Build a Rules Engine

Given a rule set in JSON, execute the rules against a object of facts. There is a set of tests and an execute function that needs to be implemented.

```
npm install
npm test
```

Example:

```typescript
const rule = {
  any: [
    { path: "foo.bar", operator: "eq", value: 1 },
    { path: "foo.baz", operator: "eq", value: 2 },
  ],
};

const facts = {
  foo: {
    bar: 1,
    baz: 2,
  },
};

expect(execute(rule, facts)).toBe(true);
```

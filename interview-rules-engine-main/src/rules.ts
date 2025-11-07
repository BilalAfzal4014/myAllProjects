type RuleOperator =
  | "eq"
  | "notEq"
  | "gt"
  | "gte"
  | "lt"
  | "lte"
  | "in"
  | "notIn";

export type Rule =
  | { type: "all"; all: Rule[] }
  | { type: "any"; any: Rule[] }
  | {
      type: "condition";
      path: string;
      operator: RuleOperator;
      value: any;
    };

//
// TODO: type and implement this function
//

export function execute(rule: Rule, facts: object) {


   if(rule.type !== 'condition'){

       for(const nestedRule: Rule of rule[rule.type as keyof Rule]){

           const result = execute(nestedRule, facts);

           if(rule.type === 'any' && !!result){
               return true;
           }

           if(rule.type === 'all' && !result){
               return false;
           }
       }

       return rule.type === 'all';

   }
   else {

       const splitPath = rule.path.split('.');
       let objectValue: any = {...facts};

       for(const path of splitPath){
           objectValue = objectValue[path];
       }

       switch (rule.operator) {
           case 'eq':
               return objectValue === rule.value;
           case 'notEq':
               return objectValue !== rule.value;
           case 'gt':
               return objectValue < rule.value;
           case 'gte':
               return objectValue <= rule.value;
           case 'lt':
               return objectValue < rule.value;
           case 'lte':
               return objectValue <= rule.value;
           case 'in':
               return rule.value.indexOf(objectValue) > -1;
           case 'notIn':
               return rule.value.indexOf(objectValue) < 0;
       }

   }

}

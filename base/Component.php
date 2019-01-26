<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace ActiveRecord\base;


/**
 * Component is the base class that implements the *property*, *event* and *behavior* features.
 *
 * Component provides the *event* and *behavior* features, in addition to the *property* feature which is implemented in
 * its parent class [[\ActiveRecord\base\Object|Object]].
 *
 * Event is a way to "inject" custom code into existing code at certain places. For example, a comment object can trigger
 * an "add" event when the user adds a comment. We can write custom code and attach it to this event so that when the event
 * is triggered (i.e. comment will be added), our custom code will be executed.
 *
 * An event is identified by a name that should be unique within the class it is defined at. Event names are *case-sensitive*.
 *
 * One or multiple PHP callbacks, called *event handlers*, can be attached to an event. You can call [[trigger()]] to
 * raise an event. When an event is raised, the event handlers will be invoked automatically in the order they were
 * attached.
 *
 * To attach an event handler to an event, call [[on()]]:
 *
 * ```php
 * $post->on('update', function ($event) {
 *     // send email notification
 * });
 * ```
 *
 * In the above, an anonymous function is attached to the "update" event of the post. You may attach
 * the following types of event handlers:
 *
 * - anonymous function: `function ($event) { ... }`
 * - object method: `[$object, 'handleAdd']`
 * - static class method: `['Page', 'handleAdd']`
 * - global function: `'handleAdd'`
 *
 * The signature of an event handler should be like the following:
 *
 * ```php
 * function foo($event)
 * ```
 *
 * where `$event` is an [[Event]] object which includes parameters associated with the event.
 *
 * You can also attach a handler to an event when configuring a component with a configuration array.
 * The syntax is like the following:
 *
 * ```php
 * [
 *     'on add' => function ($event) { ... }
 * ]
 * ```
 *
 * where `on add` stands for attaching an event to the `add` event.
 *
 * Sometimes, you may want to associate extra data with an event handler when you attach it to an event
 * and then access it when the handler is invoked. You may do so by
 *
 * ```php
 * $post->on('update', function ($event) {
 *     // the data can be accessed via $event->data
 * }, $data);
 * ```
 *
 * A behavior is an instance of [[Behavior]] or its child class. A component can be attached with one or multiple
 * behaviors. When a behavior is attached to a component, its public properties and methods can be accessed via the
 * component directly, as if the component owns those properties and methods.
 *
 * To attach a behavior to a component, declare it in [[behaviors()]], or explicitly call [[attachBehavior]]. Behaviors
 * declared in [[behaviors()]] are automatically attached to the corresponding component.
 *
 * One can also attach a behavior to a component when configuring it with a configuration array. The syntax is like the
 * following:
 *
 * ```php
 * [
 *     'as tree' => [
 *         'class' => 'Tree',
 *     ],
 * ]
 * ```
 *
 * where `as tree` stands for attaching a behavior named `tree`, and the array will be passed to [[\ActiveRecord::createObject()]]
 * to create the behavior object.
 *
 * For more details and usage information on Component, see the [guide article on components](guide:concept-components).
 *
 * @property Behavior[] $behaviors List of behaviors attached to this component. This property is read-only.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class Component extends Object
{



    /**
     * Returns the value of a component property.
     * This method will check in the following order and act accordingly:
     *
     *  - a property defined by a getter: return the getter result
     *  - a property of a behavior: return the behavior property value
     *
     * Do not call this method directly as it is a PHP magic method that
     * will be implicitly called when executing `$value = $component->property;`.
     * @param string $name the property name
     * @return mixed the property value or the value of a behavior's property
     * @throws UnknownPropertyException if the property is not defined
     * @throws InvalidCallException if the property is write-only.
     * @see __set()
     */
    public function __get($name)
    {
        $getter = 'get' . $name;
        if (method_exists($this, $getter)) {
            // read property, e.g. getName()
            return $this->$getter();
        }

        if (method_exists($this, 'set' . $name)) {
            throw new InvalidCallException('Getting write-only property: ' . get_class($this) . '::' . $name);
        }

        throw new UnknownPropertyException('Getting unknown property: ' . get_class($this) . '::' . $name);
    }

    /**
     * Sets the value of a component property.
     * This method will check in the following order and act accordingly:
     *
     *  - a property defined by a setter: set the property value
     *  - an event in the format of "on xyz": attach the handler to the event "xyz"
     *  - a behavior in the format of "as xyz": attach the behavior named as "xyz"
     *  - a property of a behavior: set the behavior property value
     *
     * Do not call this method directly as it is a PHP magic method that
     * will be implicitly called when executing `$component->property = $value;`.
     * @param string $name the property name or the event name
     * @param mixed $value the property value
     * @throws UnknownPropertyException if the property is not defined
     * @throws InvalidCallException if the property is read-only.
     * @see __get()
     */
    public function __set($name, $value)
    {
        $setter = 'set' . $name;
        if (method_exists($this, $setter)) {
            // set property
            $this->$setter($value);

            return;
        }


        if (method_exists($this, 'get' . $name)) {
            throw new InvalidCallException('Setting read-only property: ' . get_class($this) . '::' . $name);
        }

        throw new UnknownPropertyException('Setting unknown property: ' . get_class($this) . '::' . $name);
    }

    /**
     * Checks if a property is set, i.e. defined and not null.
     * This method will check in the following order and act accordingly:
     *
     *  - a property defined by a setter: return whether the property is set
     *  - a property of a behavior: return whether the property is set
     *  - return `false` for non existing properties
     *
     * Do not call this method directly as it is a PHP magic method that
     * will be implicitly called when executing `isset($component->property)`.
     * @param string $name the property name or the event name
     * @return bool whether the named property is set
     * @see http://php.net/manual/en/function.isset.php
     */
    public function __isset($name)
    {
        $getter = 'get' . $name;
        if (method_exists($this, $getter)) {
            return $this->$getter() !== null;
        }


        return false;
    }

    /**
     * Sets a component property to be null.
     * This method will check in the following order and act accordingly:
     *
     *  - a property defined by a setter: set the property value to be null
     *  - a property of a behavior: set the property value to be null
     *
     * Do not call this method directly as it is a PHP magic method that
     * will be implicitly called when executing `unset($component->property)`.
     * @param string $name the property name
     * @throws InvalidCallException if the property is read only.
     * @see http://php.net/manual/en/function.unset.php
     */
    public function __unset($name)
    {
        $setter = 'set' . $name;
        if (method_exists($this, $setter)) {
            $this->$setter(null);
            return;
        }

        throw new InvalidCallException('Unsetting an unknown or read-only property: ' . get_class($this) . '::' . $name);
    }


    /**
     * Returns a value indicating whether a property is defined for this component.
     * A property is defined if:
     *
     * - the class has a getter or setter method associated with the specified name
     *   (in this case, property name is case-insensitive);
     * - the class has a member variable with the specified name (when `$checkVars` is true);
     * - an attached behavior has a property of the given name (when `$checkBehaviors` is true).
     *
     * @param string $name the property name
     * @param bool $checkVars whether to treat member variables as properties
     * @param bool $checkBehaviors whether to treat behaviors' properties as properties of this component
     * @return bool whether the property is defined
     * @see canGetProperty()
     * @see canSetProperty()
     */
    public function hasProperty($name, $checkVars = true, $checkBehaviors = true)
    {
        return $this->canGetProperty($name, $checkVars, $checkBehaviors) || $this->canSetProperty($name, false, $checkBehaviors);
    }

    /**
     * Returns a value indicating whether a property can be read.
     * A property can be read if:
     *
     * - the class has a getter method associated with the specified name
     *   (in this case, property name is case-insensitive);
     * - the class has a member variable with the specified name (when `$checkVars` is true);
     * - an attached behavior has a readable property of the given name (when `$checkBehaviors` is true).
     *
     * @param string $name the property name
     * @param bool $checkVars whether to treat member variables as properties
     * @param bool $checkBehaviors whether to treat behaviors' properties as properties of this component
     * @return bool whether the property can be read
     * @see canSetProperty()
     */
    public function canGetProperty($name, $checkVars = true, $checkBehaviors = true)
    {
        if (method_exists($this, 'get' . $name) || $checkVars && property_exists($this, $name)) {
            return true;
        }
        return false;
    }

    /**
     * Returns a value indicating whether a property can be set.
     * A property can be written if:
     *
     * - the class has a setter method associated with the specified name
     *   (in this case, property name is case-insensitive);
     * - the class has a member variable with the specified name (when `$checkVars` is true);
     * - an attached behavior has a writable property of the given name (when `$checkBehaviors` is true).
     *
     * @param string $name the property name
     * @param bool $checkVars whether to treat member variables as properties
     * @param bool $checkBehaviors whether to treat behaviors' properties as properties of this component
     * @return bool whether the property can be written
     * @see canGetProperty()
     */
    public function canSetProperty($name, $checkVars = true, $checkBehaviors = true)
    {
        if (method_exists($this, 'set' . $name) || $checkVars && property_exists($this, $name)) {
            return true;
        }
        return false;
    }

    /**
     * Returns a value indicating whether a method is defined.
     * A method is defined if:
     *
     * - the class has a method with the specified name
     * - an attached behavior has a method with the given name (when `$checkBehaviors` is true).
     *
     * @param string $name the property name
     * @param bool $checkBehaviors whether to treat behaviors' methods as methods of this component
     * @return bool whether the method is defined
     */
    public function hasMethod($name, $checkBehaviors = true)
    {
        if (method_exists($this, $name)) {
            return true;
        }
        return false;
    }



}

How to set configs:
==================



um, do we even do that anymore?

Try
`Authority:setCurrentUser($User)`
(defaults to Auth::user() if not set)


And then set up some rules

`Authority::addRule()`
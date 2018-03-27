<?php

use Swagger\Annotations as SWG;

/**
 *
 * @SWG\Info(title="API - Prague Airport Transfers s.r.o.", version="0.1")
 *
 *
 * @SWG\Swagger(
 *     schemes={"https"}, host="pat.dev", basePath="",
 *     @SWG\SecurityScheme(
 *          name="Authorization",
 *          type="apiKey",
 *          securityDefinition="Authorization",
 *          in="header"
 *     ),
 *     security={{"Authorization": {}}},
 *
 * )
 *
 * @SWG\Definition(
 *      definition="FreeTime",
 * 	    @SWG\Property(property="driverId", type="number"),
 * 	    @SWG\Property(property="carId", type="number"),
 *      @SWG\Property(property="date", type="string"),
 *      @SWG\Property(property="times", type="array", @SWG\Items(type="string")),
 * ),
 *
 * @SWG\Definition(
 *      definition="Paginator",
 * 	    @SWG\Property(property="paginator", type="number"),
 * 	    @SWG\Property(property="pagesCount", type="number"),
 * 	    @SWG\Property(property="itemsPerPage", type="number"),
 * 	),
 *
 *
 *  @SWG\Definition(
 *      definition="Country",
 * 	    @SWG\Property(property="code", type="string"),
 * 	    @SWG\Property(property="name", type="string"),
 * 	),
 *
 *
 * @SWG\Post(
 *     path="/api/login", summary="log user in", tags={"login"},
 *     @SWG\Parameter(name="body", in="body", @SWG\Schema(
 *          @SWG\Property(property="email", type="string"),
 *          @SWG\Property(property="password", type="string"),
 *     )),
 *     @SWG\Response(response="200", ref="#/definitions/Token", description="Returns user"),
 * )
 *
 * @SWG\Definition(
 *      definition="Token",
 *      @SWG\Property(property="token", type="string"),
 * ),
 *
 */
